<?php

namespace Aune\ProductGridCategoryFilter\Helper;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

class Categories
{
    const FULL_NAME_SEPARATOR = ' > ';

    /**
     * @var CategoryCollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * Category id to model associative array
     *
     * @var array
     */
    private $categoriesById;

    /**
     * Category id to full name (with path) associative array
     *
     * @var array
     */
    private $categoriesFullNames;

    /**
     * @param CategoryCollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        CategoryCollectionFactory $categoryCollectionFactory
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * Returns all active categories
     */
    public function getCategories()
    {
        if (is_null($this->categoriesById)) {
            $collection = $this->categoryCollectionFactory->create()
                ->addAttributeToSelect('name')
                ->addAttributeToFilter('level', ['gt' => 1])
                ->addAttributeToFilter('is_active', ['gt' => 0]);

            $this->categoriesById = [];
            foreach ($collection as $category) {
                $this->categoriesById[$category->getId()] = $category;
            }
        }

        return $this->categoriesById;
    }

    /**
     * Returns all active categories full names in associative array
     */
    public function getCategoryFullNames()
    {
        if (is_null($this->categoriesFullNames)) {
            $categories = $this->getCategories();

            $this->categoriesFullNames = [];
            foreach ($categories as $id => $category) {
                $current = $category;
                $name = $category->getName();

                while (isset($categories[$current->getParentId()])) {
                    $current = $categories[$current->getParentId()];
                    $name = $current->getName() . self::FULL_NAME_SEPARATOR . $name;
                }
                
                $this->categoriesFullNames[$id] = $name;
            }

            asort($this->categoriesFullNames);
        }

        return $this->categoriesFullNames;
    }
}
