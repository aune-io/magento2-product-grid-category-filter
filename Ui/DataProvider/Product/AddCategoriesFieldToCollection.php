<?php

namespace Aune\ProductGridCategoryFilter\Ui\DataProvider\Product;

use Magento\Framework\Data\Collection;
use Magento\Ui\DataProvider\AddFieldToCollectionInterface;
use Aune\ProductGridCategoryFilter\Observer\ProductCollectionLoadAfter;

class AddCategoriesFieldToCollection implements AddFieldToCollectionInterface
{
    /**
     * @inheritdoc
     */
    public function addField(Collection $collection, $field, $alias = null)
    {
        $collection->setData(ProductCollectionLoadAfter::FLAG_LOAD_CATEGORIES, true);
    }
}
