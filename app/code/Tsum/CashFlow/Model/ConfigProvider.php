<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigProvider
{
    public const string ONES_CODE_FIELD = 'ones_id';

    public const string XML_PATH_BEGIN_DATE = 'cashflow/aggregation/begin_date';
    public const string XML_PATH_CLOSE_DATE = 'cashflow/aggregation/close_date';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function getBeginDate(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_BEGIN_DATE);
    }

    public function getCloseDate(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_CLOSE_DATE);
    }
}
