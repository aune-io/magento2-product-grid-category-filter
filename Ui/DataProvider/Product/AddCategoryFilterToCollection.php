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

        $productIds = $this->connection->fetchCol(
            $this->getIdSelect(intval($condition['eq']))
        );
        
        $collection->getSelect()->where('e.entity_id IN (?)', $productIds);
    }
    
    /**
     * 
     */
    private function getIdSelect(int $categoryId)
    {
        $tableName = $this->resource->getTableName(
            $this->helperData->getCategoryProductTableName(HelperData::ACTION_FILTER)
        );
        
        // Apply "No Category" filter
        if ($categoryId == -1) {
            $entityTableName = $this->resource->getTableName('catalog_product_entity');
            
            return $this->connection->select()
                ->from(['E' => $entityTableName], ['E.entity_id'])
                ->joinLeft(
                    ['C' => $tableName],
                    'E.entity_id = C.product_id',
                    []
                )
                ->where('C.category_id IS NULL');
        }
        
        // Apply standard category filter
        return $this->connection->select()
            ->from($tableName, ['product_id'])
            ->where('category_id = ?', $categoryId);
    }
}
