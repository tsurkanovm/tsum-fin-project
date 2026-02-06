<?php
declare(strict_types=1);
namespace Tsum\CashFlow\Model\Aggregation;

use Magento\Framework\App\ResourceConnection;

class GetCurrentAggregationDate
{

    public function __construct(
        private readonly ResourceConnection $resourceConnection,
    ) {
    }
    public function execute(): string
    {
        $connection = $this->resourceConnection->getConnection();
        $incomes = $connection->select()
            ->from(
                ['tsum_incomes' => $connection->getTableName('tsum_incomes')],
            )
            ->order('registration_date', 'DESC')
        ->limit(1);

        $incomeRow = $connection->fetchAll($incomes);

        return $incomeRow[0]['registration_date'];
    }
}
