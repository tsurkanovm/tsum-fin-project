<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Aggregation;

class Calculator
{
    public function calculate(): void
    {
        // 1. get status
        // 2. run calculateTurnoverForPeriods - calculate each month
        // 3. get status date if it not null, get current date - calculate only for this date for now
        // todo calculates for each year + previous month
    }

    public function calculateAllPeriods(): void
    {
        // 1. get begin date and current date
        // 2. truncate turnover and remains
        // 3. get all month periods from begin to current
        // 4. run turnover calculation
        // 5. run remains for current date

    }

    private function calculateTurnoverForPeriods(array $periods): void
    {

    }

    private function calculateRemainsOnDate(string $toDate): void
    {

    }
}
