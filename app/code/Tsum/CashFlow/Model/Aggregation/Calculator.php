<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Aggregation;

use DateTime;
use DateInterval;
use Tsum\CashFlow\Model\Aggregation\Status\StatusListManager;
use Tsum\CashFlow\Model\ConfigProvider;

class Calculator
{
    public function __construct(
        private readonly StatusListManager $statusListManager,
        private readonly ConfigProvider $configProvider,
        private readonly GetCurrentAggregationDate $getCurrentAggregationDate,
        private readonly AggregationRepository $aggregationRepository,
    ) {
    }

    public function calculate(): void
    {
        $statusList = $this->statusListManager->get();
        $turnoverPeriods = $statusList->turnovers;
        if (!empty($turnoverPeriods)) {
            $this->calculateTurnoverForPeriods($turnoverPeriods);
        }

        $remainDate = $statusList->remains;
        if (!empty($remainDate)) {
            // todo calculates for each year + previous month
            $this->calculateRemainsOnDate($remainDate);
        }

        $this->statusListManager->delete();
    }

    public function calculateAllPeriods(): void
    {
        $beginDate = $this->configProvider->getBeginDate();
        $currentDate = $this->getCurrentAggregationDate->execute();
        // 3. get all month periods from begin to current
        $turnoverPeriods = $this->getTurnoverPeriods($beginDate, $currentDate);

        //todo cover by transaction
        $this->aggregationRepository->truncateAggregateData(Type::REMAIN);
        $this->aggregationRepository->truncateAggregateData(Type::TURNOVER);
        $this->calculateTurnoverForPeriods($turnoverPeriods);
        $this->calculateRemainsOnDate($currentDate);
    }

    private function calculateTurnoverForPeriods(array $periods): void
    {

    }

    private function calculateRemainsOnDate(string $toDate): void
    {

    }

    private function getTurnoverPeriods(string $beginDate, string $currentDate): array
    {
        $periods = [];
        $start = DateTime::createFromFormat('n/j/Y', $beginDate);
        $end = DateTime::createFromFormat('n/j/Y', $currentDate);

        $current = clone $start;
        $current->modify('last day of this month');

        while ($current <= $end) {
            $periods[] = $current->format('n/j/Y');
            $current->add(new DateInterval('P1D'));
            $current->modify('last day of this month');
        }

        return $periods;
    }
}
