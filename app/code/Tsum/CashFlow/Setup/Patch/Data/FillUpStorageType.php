<?php

namespace Tsum\CashFlow\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Tsum\CashFlow\Model\StorageType;

class FillUpStorageType implements DataPatchInterface
{
    const INIT_DATA = [
        1 => 'Cashless',
        2 => 'Cash',
        3 => 'Deposit'];

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $setup = $this->moduleDataSetup;
        $setup->getConnection()->insertArray(
            'tsum_storage_type',
            ['title'],
            array_values(self::INIT_DATA)
        );

        $this->moduleDataSetup->endSetup();
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
