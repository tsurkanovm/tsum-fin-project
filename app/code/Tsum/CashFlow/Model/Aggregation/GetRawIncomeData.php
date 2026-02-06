<?php
declare(strict_types=1);
namespace Tsum\CashFlow\Model\Aggregation;

use Magento\Framework\App\ResourceConnection;

class GetRawIncomeData
{
    public function __construct(
        private readonly ResourceConnection $resourceConnection,
    ) {
    }

    public function execute(string $date): array
    {
        return [];
    }
}
