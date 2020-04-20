<?php


namespace Tsum\CashFlow\Setup\Patch\Data;


use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class ReduceFlatAttributes implements DataPatchInterface
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
     * ApplyAttributesUpdate constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
//        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
//        $attributeList = [
//            'gift_message_available',
//            'msrp',
//            'msrp_display_actual_price_type',
//            'news_from_date',
//            'news_to_date',
//            'price_type',
//            'price_view',
//            'required_options',
//            'sku_type',
//            'special_from_date',
//            'special_price',
//            'special_to_date',
//            'swatch_image',
//            'ts_dimensions_height',
//            'weight_type',
//            'minimal_price',
//            'cost',
//            'tier_price',
//            'tax_class_id',
//            'weight',
//        ];
//        foreach ($attributeList as $field) {
//            $applyTo = explode(
//                ',',
//                $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $field, 'apply_to')
//            );
//            if (!in_array(\Magento\Catalog\Model\Product\Type::TYPE_BUNDLE, $applyTo)) {
//                $applyTo[] = \Magento\Catalog\Model\Product\Type::TYPE_BUNDLE;
//                $eavSetup->updateAttribute(
//                    \Magento\Catalog\Model\Product::ENTITY,
//                    $field,
//                    'apply_to',
//                    implode(',', $applyTo)
//                );
//            }
//        }
//
//        $applyTo = explode(',', $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, 'cost', 'apply_to'));
//        unset($applyTo[array_search(\Magento\Catalog\Model\Product\Type::TYPE_BUNDLE, $applyTo)]);
//        $eavSetup->updateAttribute(\Magento\Catalog\Model\Product::ENTITY, 'cost', 'apply_to', implode(',', $applyTo));

    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
