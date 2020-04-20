<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Plugin\Product\Flat;

use Magento\Catalog\Model\Attribute\Config;

class Indexer
{
    /**
     * @var Config
     */
    private $attributeConfig;

    /**
     * Indexer constructor.
     * @param Config $attributeConfig
     */
    public function __construct(Config $attributeConfig)
    {
        $this->attributeConfig = $attributeConfig;
    }

    public function afterGetAttributeCodes(\Magento\Catalog\Helper\Product\Flat\Indexer $subject, array $result)
    {
        if ($flatAttributes = $this->attributeConfig->getAttributeNames('flat_attributes')) {
            return $flatAttributes;
        }

        return $result;
    }
}
