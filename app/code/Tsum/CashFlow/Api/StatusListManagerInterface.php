<?php

namespace Tsum\CashFlow\Api;

use Tsum\CashFlow\Model\Aggregation\Status\StatusList;

interface StatusListManagerInterface
{
    /**
     * @return \Tsum\CashFlow\Model\Aggregation\Status\StatusList
     */
    public function get() : StatusList;

    /**
     * @param string $remains
     * @param string|null $turnovers
     * @return void
     */
    public function update(string $remains, ?string $turnovers): void;
}
