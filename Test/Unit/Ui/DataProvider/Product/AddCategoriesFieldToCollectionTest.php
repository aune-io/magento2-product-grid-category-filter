<?php

namespace Aune\ProductGridCategoryFilter\Test\Unit\Ui\DataProvider\Product;

use Magento\Framework\Data\Collection;
use PHPUnit\Framework\TestCase;
use Aune\ProductGridCategoryFilter\Observer\ProductCollectionLoadAfter;
use Aune\ProductGridCategoryFilter\Ui\DataProvider\Product\AddCategoriesFieldToCollection;

#[\PHPUnit\Framework\Attributes\CoversClass(\Aune\ProductGridCategoryFilter\Ui\DataProvider\Product\AddCategoriesFieldToCollection::class)]
class AddCategoriesFieldToCollectionTest extends TestCase
{
    /**
     * The grid asks for the field: the collection must be flagged so the load-after observer adds the categories.
     * (The flag API is setFlag/getFlag; setData does not exist on collections.)
     */
    public function testAddFieldFlagsTheCollection()
    {
        $collection = $this->createMock(Collection::class);
        $collection->expects(self::once())
            ->method('setFlag')
            ->with(ProductCollectionLoadAfter::FLAG_LOAD_CATEGORIES, true);

        (new AddCategoriesFieldToCollection())->addField($collection, 'category_id');
    }
}
