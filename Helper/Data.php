<?php

namespace Aune\ProductGridCategoryFilter\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Data
{
    const XML_PATH_ANCHOR_SHOW = 'catalog/aune_productgridcategoryfilter/anchor_show';
    const XML_PATH_ANCHOR_FILTER = 'catalog/aune_productgridcategoryfilter/anchor_filter';
    const XML_PATH_STORE_ID = 'catalog/aune_productgridcategoryfilter/store_id';
    
    const ACTION_SHOW = 'show';
    const ACTION_FILTER = 'filter';
    
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    
    /**
     * @var ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }
    
    /**
     * Return wether the anchor category associations should be displayed too
     * in the product grid, or only direct assignations
     *
     * @return boolean
     */
    public function isAnchorShowEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ANCHOR_SHOW
        );
    }
    
    /**
     * Return wether the filter should be applied to anchor categories
     * or only direct assignations
     *
     * @return boolean
     */
    public function isAnchorFilterEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ANCHOR_FILTER
        );
    }
    
    /**
     * Return reference store_id
     *
     * @return boolean
     */
    public function getStoreId()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STORE_ID
        );
    }
    
    /**
     * Returns the table name after checking if the action should be applied
     * to direct assignments or to anchor, too
     */
    public function getCategoryProductTableName($action)
    {
        $storeId = $this->getStoreId();
        if (!$storeId) {
            $storeId = 1;
        }
        
        $baseTableName = 'catalog_category_product';
        $anchorTableName = 'catalog_category_product_index_store' . $storeId;
        
        if (($action == self::ACTION_SHOW && $this->isAnchorShowEnabled()) ||
            ($action == self::ACTION_FILTER && $this->isAnchorFilterEnabled())) {
            
            return $anchorTableName;
        }
        
        return $baseTableName;
    }
}
