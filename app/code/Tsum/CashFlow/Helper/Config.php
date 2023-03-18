<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    public const ONES_CODE_FIELD = 'ones_id';
    public const XML_PATH_STORAGE_ATTR_SET = 'cash_flow/general/storage_attribute_set';

    /**
     * @return string
     */
    public function getStorageAttributeSetId(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STORAGE_ATTR_SET,
            ScopeInterface::SCOPE_STORE
        );
    }
}
