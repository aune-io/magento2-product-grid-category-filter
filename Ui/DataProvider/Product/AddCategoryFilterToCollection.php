<?php

namespace Aune\ProductGridCategoryFilter\Ui\DataProvider\Product;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Collection;
use Magento\Ui\DataProvider\AddFilterToCollectionInterface;
use Aune\ProductGridCategoryFilter\Helper\Data as HelperData;

class AddCategoryFilterToCollection implements AddFilterToCollectionInterface
{
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
    public function addFilter(Collection $collection, $field, $condition = null)
    {
        if (empty($condition['eq'])) {
            return;
        }
        
        $tableName = $this->resource->getTableName(
            $this->helperData->getCategoryProductTableName(HelperData::ACTION_FILTER)
        );
        $select = $this->connection->select()
            ->from($tableName, ['product_id'])
            ->where('category_id = ?', $condition['eq']);
        
        $data = $this->connection->fetchAll($select);
        $productIds = array_column($data, 'product_id');
        
        $collection->getSelect()->where('e.entity_id IN (?)', $productIds);
    }
}
