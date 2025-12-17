<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Aggregation;

class Processor
{
    public function fullProcessing(): void
    {
        // get first date from config
        $systemStartDate = '';//$this->scopeConfig->getDateFromStarAggregation();
        $this->byDateProcessing($systemStartDate);
    }

    public function byDateProcessing(string $sinceTimeStamp)
    {
        // 1. Calculate (SQL) turnovers for all periods from date (grouped by year) => array
        // 2. Create aggregation for particular period by array + previous remains
        // we will have remains for all storage+cur combinations if it has more that one turnover in any period
    }
}
