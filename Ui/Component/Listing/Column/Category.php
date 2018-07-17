<?php

namespace Aune\ProductGridCategoryFilter\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Aune\ProductGridCategoryFilter\Helper\Categories as CategoriesHelper;

class Category extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var CategoriesHelper
     */
    private $categoriesHelper;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param CategoriesHelper $categoriesHelper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CategoriesHelper $categoriesHelper,
        array $components = [],
        array $data = []
    ) {
        $this->categoriesHelper = $categoriesHelper;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @inheritdoc
     */
    public function prepareDataSource(array $dataSource)
    {
return $dataSource;
        $categoryFullNames = $this->categoriesHelper->getCategoryFullNames();

        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $categories = [];
                foreach ($item[$fieldName] as $categoryId) {
                    if (!isset($categoryFullNames[$categoryId])) {
                        continue;
                    }
                    $categories[] = $categoryFullNames[$categoryId];
                }
                $item[$fieldName] = implode(', ', $categories);
            }
        }

        return $dataSource;
    }
}
