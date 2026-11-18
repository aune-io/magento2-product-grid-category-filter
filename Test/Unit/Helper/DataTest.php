<?php

namespace Aune\ProductGridCategoryFilter\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\TestCase;
use Aune\ProductGridCategoryFilter\Helper\Data as HelperData;

#[\PHPUnit\Framework\Attributes\CoversClass(\Aune\ProductGridCategoryFilter\Helper\Data::class)]
class DataTest extends TestCase
{
    /**
     * @var ScopeConfigInterface|\PHPUnit\Framework\MockObject\Stub
     */
    private $scopeConfigMock;

    /**
     * @var HelperData
     */
    private $helperData;

    protected function setUp(): void
    {
        $this->scopeConfigMock = $this->createStub(ScopeConfigInterface::class);
        $this->helperData = new HelperData($this->scopeConfigMock);
    }

    /**
     * @param bool $anchorShow
     * @param bool $anchorFilter
     * @param mixed $storeId
     */
    private function configure(bool $anchorShow, bool $anchorFilter, $storeId): void
    {
        $this->scopeConfigMock->method('isSetFlag')->willReturnMap([
            [HelperData::XML_PATH_ANCHOR_SHOW, 'default', null, $anchorShow],
            [HelperData::XML_PATH_ANCHOR_FILTER, 'default', null, $anchorFilter],
        ]);
        $this->scopeConfigMock->method('getValue')->willReturnMap([
            [HelperData::XML_PATH_STORE_ID, 'default', null, $storeId],
        ]);
    }

    /**
     * @dataProvider getTableNameDataProvider
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('getTableNameDataProvider')]
    public function testGetCategoryProductTableName(
        bool $anchorShow,
        bool $anchorFilter,
        $storeId,
        string $action,
        string $expected
    ) {
        $this->configure($anchorShow, $anchorFilter, $storeId);

        self::assertSame($expected, $this->helperData->getCategoryProductTableName($action));
    }

    /**
     * @return array
     */
    public static function getTableNameDataProvider()
    {
        $direct = 'catalog_category_product';
        return [
            'anchor disabled, show' => [false, false, null, HelperData::ACTION_SHOW, $direct],
            'anchor disabled, filter' => [false, false, null, HelperData::ACTION_FILTER, $direct],
            'anchor show only, show' => [true, false, null, HelperData::ACTION_SHOW, 'catalog_category_product_index_store1'],
            'anchor show only, filter' => [true, false, null, HelperData::ACTION_FILTER, $direct],
            'anchor filter only, filter' => [false, true, null, HelperData::ACTION_FILTER, 'catalog_category_product_index_store1'],
            'anchor filter only, show' => [false, true, null, HelperData::ACTION_SHOW, $direct],
            'custom store id' => [true, true, '3', HelperData::ACTION_SHOW, 'catalog_category_product_index_store3'],
            'empty store id falls back to 1' => [true, true, '', HelperData::ACTION_FILTER, 'catalog_category_product_index_store1'],
        ];
    }
}
