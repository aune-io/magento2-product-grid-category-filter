<?php

namespace Aune\ProductGridCategoryFilter\Model\Source;

use Magento\Framework\Option\ArrayInterface;
use Aune\ProductGridCategoryFilter\Helper\Categories as CategoriesHelper;

class Categories implements ArrayInterface
{
    /**
     * @var CategoriesHelper
     */
    private $categoriesHelper;

    /**
     * @param CategoriesHelper $categoriesHelper
     */
    public function __construct(
        CategoriesHelper $categoriesHelper
    ) {
        $this->categoriesHelper = $categoriesHelper;
    }
    
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $options = [
            [
                'value' => -1,
                'label' => __('No Category')
            ]
        ];
        $categoryFullNames = $this->categoriesHelper->getCategoryFullNames();
        
        foreach ($categoryFullNames as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label,
            ];
        }

        return $options;
    }
}
