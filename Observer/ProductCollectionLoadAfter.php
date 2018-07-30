<?php

namespace Aune\ProductGridCategoryFilter\Observer;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Aune\ProductGridCategoryFilter\Helper\Data as HelperData;

class ProductCollectionLoadAfter implements ObserverInterface
{
    const FLAG_LOAD_CATEGORIES = 'flag_load_categories';

    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var \Magento\Framework\DB\Adapter\Pdo\Mysql
     */
    private $connection;
    
    /**
     * @var HelperData
     */
    private $helperData;
    
    /**
     * @param ResourceConnection $resource
     * @param HelperData $helperData
     */
    public function __construct(
        ResourceConnection $resource,
        HelperData $helperData
    ) {
        $this->resource = $resource;
        $this->helperData = $helperData;

        $this->connection = $this->resource->getConnection(
            ResourceConnection::DEFAULT_CONNECTION
        );
    }

    /**
     * @inheritdoc
     */
    public function execute(Observer $observer)
    {
        $collection = $observer->getCollection();
        if (!$collection->getData(self::FLAG_LOAD_CATEGORIES)) {
            return;
        }

        // Get loaded product ids
        $categories = [];
        foreach ($collection as $product) {
            $categories[$product->getId()] = [];
        }

        if (count($categories) == 0) {
            return;
        }

        // Get product/category associations for loaded products
        $tableName = $this->resource->getTableName(
            $this->helperData->getCategoryProductTableName(HelperData::ACTION_SHOW)
        );
        $select = $this->connection->select()
            ->from($tableName)
            ->where('product_id IN (?)', array_keys($categories));
        
        $data = $this->connection->fetchAll($select);

        $categories = [];
        foreach ($data as $row) {
            $categories[$row['product_id']][] = $row['category_id'];
        }

        // Assign ids back to products array
        foreach ($collection as $product) {
            if (!isset($categories[$product->getId()])) {
                continue;
            }

            $product->setData('category_id', $categories[$product->getId()]);
        }

        return $this;
    }
}
