<?php

namespace Aune\ProductGridCategoryFilter\Test\Unit\Observer;

use ArrayIterator;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Data\Collection;
use Magento\Framework\Event\Observer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Aune\ProductGridCategoryFilter\Helper\Data as HelperData;
use Aune\ProductGridCategoryFilter\Observer\ProductCollectionLoadAfter;

#[\PHPUnit\Framework\Attributes\CoversClass(\Aune\ProductGridCategoryFilter\Observer\ProductCollectionLoadAfter::class)]
class ProductCollectionLoadAfterTest extends TestCase
{
    /**
     * @var AdapterInterface|MockObject
     */
    private $connectionMock;

    /**
     * @var ProductCollectionLoadAfter
     */
    private $observer;

    protected function setUp(): void
    {
        $this->connectionMock = $this->createMock(AdapterInterface::class);

        $resourceMock = $this->createStub(ResourceConnection::class);
        $resourceMock->method('getConnection')->willReturn($this->connectionMock);
        $resourceMock->method('getTableName')->willReturnArgument(0);

        $helperMock = $this->createStub(HelperData::class);
        $helperMock->method('getCategoryProductTableName')->willReturn('catalog_category_product');

        $this->observer = new ProductCollectionLoadAfter($resourceMock, $helperMock);
    }

    /**
     * @param bool $flag
     * @param DataObject[] $products
     * @return Observer
     */
    private function getEvent(bool $flag, array $products): Observer
    {
        $collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getFlag', 'getIterator'])
            ->getMock();
        $collection->method('getFlag')->with(ProductCollectionLoadAfter::FLAG_LOAD_CATEGORIES)->willReturn($flag);
        $collection->method('getIterator')->willReturnCallback(fn () => new ArrayIterator($products));

        return new Observer(['collection' => $collection]);
    }

    /**
     * Collections not asked for categories (any other admin product collection) must be left alone and
     * must not cost a query.
     */
    public function testCollectionWithoutFlagIsIgnored()
    {
        $product = new DataObject(['id' => 1]);
        $this->connectionMock->expects(self::never())->method('select');
        $this->connectionMock->expects(self::never())->method('fetchAll');

        $this->observer->execute($this->getEvent(false, [$product]));

        self::assertNull($product->getData('category_id'));
    }

    public function testFlaggedEmptyCollectionRunsNoQuery()
    {
        $this->connectionMock->expects(self::never())->method('select');

        $this->observer->execute($this->getEvent(true, []));
    }

    public function testFlaggedCollectionGetsCategoryIds()
    {
        $products = [
            new DataObject(['id' => 1]),
            new DataObject(['id' => 2]),
            new DataObject(['id' => 3]),
        ];

        $selectMock = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['from', 'where'])
            ->getMock();
        $selectMock->method('from')->with('catalog_category_product')->willReturnSelf();
        $selectMock->expects(self::once())->method('where')->with('product_id IN (?)', [1, 2, 3])->willReturnSelf();

        $this->connectionMock->method('select')->willReturn($selectMock);
        $this->connectionMock->expects(self::once())->method('fetchAll')->with($selectMock)->willReturn([
            ['product_id' => 1, 'category_id' => 10],
            ['product_id' => 1, 'category_id' => 11],
            ['product_id' => 3, 'category_id' => 12],
        ]);

        $this->observer->execute($this->getEvent(true, $products));

        self::assertSame([10, 11], $products[0]->getData('category_id'));
        self::assertNull($products[1]->getData('category_id'), 'product without categories is left untouched');
        self::assertSame([12], $products[2]->getData('category_id'));
    }
}
