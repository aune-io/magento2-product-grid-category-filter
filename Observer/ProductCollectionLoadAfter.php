<?php

namespace Aune\ProductGridCategoryFilter\Observer;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ProductCollectionLoadAfter implements ObserverInterface
{
    const FLAG_LOAD_CATEGORIES = 'flag_load_categories';

    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @var \Magento\Framework\DB\Adapter\Pdo\Mysql
     */
    protected $connection;

    /**
     * @param ResourceConnection $resource
     */
    public function __construct(
        ResourceConnection $resource
    ) {
        $this->resource = $resource;

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
        $select = $this->connection->select()
            ->from($this->resource->getTableName('catalog_category_product'))
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
