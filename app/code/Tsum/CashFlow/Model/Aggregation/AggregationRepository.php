<?php
declare(strict_types=1);
namespace Tsum\CashFlow\Model\Aggregation;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;

class AggregationRepository
{
    private ?AdapterInterface $connection = null;

    public function __construct(
        private readonly ResourceConnection $resourceConnection,
    ) {
    }

    // todo - $aggregationType should be Enum - Remains, Turnovers
    public function save(Type $aggregationType, array $data): void
    {
        $this->getConnection()->insertMultiple($aggregationType->value, $data);
    }

    public function truncateAggregateData(Type $aggregationType): void
    {
        $truncateQuery = "TRUNCATE {$aggregationType->value}";
        $this->getConnection()->query($truncateQuery);
    }

    public function getRemain(int $storageId, string $currency): float
    {
        return 1000;
    }

    public function getMonthTurnover(int $cfItemId, string $date): array
    {
        return [
            'UAH' => '1000',
            'USD' => '100',
            'EUR' => '10',
        ];
    }

    private function getConnection(): AdapterInterface
    {
        if (!$this->connection) {
            $this->connection = $this->resourceConnection->getConnection();
        }

        return $this->connection;
    }
}
