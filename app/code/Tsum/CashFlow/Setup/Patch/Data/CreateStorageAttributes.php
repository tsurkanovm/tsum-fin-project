<?php
declare(strict_types=1);
namespace Tsum\CashFlow\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Tsum\CashFlow\Model\Storage;

class CreateStorageAttributes implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply() : void
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(
            Product::ENTITY,
            Storage::TYPE_ATTR_CODE,
            [
                'type' => 'varchar',
                'label' => 'Storage Type',
                'input' => 'select',
                'required' => false,
                'sort_order' => 3,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'Product Details',
                'user_defined' => true,
                'searchable' => true,
                'filterable' => true,
                'apply_to' => 'virtual',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true,
                'source' => \Tsum\CashFlow\Model\Source\Storage\Type::class
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            Storage::CURRENCY_ATTR_CODE,
            [
                'type' => 'varchar',
                'label' => 'Currency',
                'input' => 'select',
                'required' => false,
                'sort_order' => 4,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'Product Details',
                'user_defined' => true,
                'searchable' => true,
                'filterable' => true,
                'apply_to' => 'virtual',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true,
                'source' => \Tsum\CashFlow\Model\Source\Storage\Currency::class
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies() : array
    {
        return [
            FillUpStorageType::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases() : array
    {
        return [];
    }
}
