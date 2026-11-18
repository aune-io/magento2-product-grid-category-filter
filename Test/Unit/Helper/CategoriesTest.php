<?php

namespace Aune\ProductGridCategoryFilter\Test\Unit\Helper;

use ArrayIterator;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\DataObject;
use PHPUnit\Framework\TestCase;
use Aune\ProductGridCategoryFilter\Helper\Categories;

#[\PHPUnit\Framework\Attributes\CoversClass(\Aune\ProductGridCategoryFilter\Helper\Categories::class)]
class CategoriesTest extends TestCase
{
    private function getHelper(array $rows): Categories
    {
        $categories = [];
        foreach ($rows as [$id, $name, $parentId]) {
            $categories[] = new DataObject(['id' => $id, 'name' => $name, 'parent_id' => $parentId]);
        }

        $collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['addAttributeToSelect', 'addAttributeToFilter', 'getIterator'])
            ->getMock();
        $collection->method('addAttributeToSelect')->willReturnSelf();
        $collection->expects(self::exactly(2))->method('addAttributeToFilter')->willReturnSelf();
        $collection->method('getIterator')->willReturn(new ArrayIterator($categories));

        $factory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();
        $factory->expects(self::once())->method('create')->willReturn($collection);

        return new Categories($factory);
    }

    public function testGetCategoryFullNames()
    {
        $helper = $this->getHelper([
            [5, 'Shoes', 3],
            [3, 'Men', 2],
            [2, 'Default Category', 1], // parent (1) is not an active level>1 category: path stops here
            [7, 'Bags', 2],
        ]);

        self::assertSame(
            [
                2 => 'Default Category',
                7 => 'Default Category > Bags',
                3 => 'Default Category > Men',
                5 => 'Default Category > Men > Shoes',
            ],
            $helper->getCategoryFullNames()
        );

        // Sorted alphabetically by full name, keys kept. Result is cached: the collection factory expectation (once) would fail otherwise
        $helper->getCategoryFullNames();
    }
}
