<?php

namespace Tsum\CashFlow\Api;

use Tsum\CashFlow\Model\Aggregation\Status\StatusList;

interface StatusListManagerInterface
{
    public function get() : StatusList;

    public function update(string $remains, ?string $turnovers): void;
}
