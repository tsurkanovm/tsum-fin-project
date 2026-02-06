<?php
declare(strict_types=1);
namespace Tsum\CashFlow\Model\Aggregation;

enum Type: string
{
    Case REMAIN = 'tsum_cf_remains';
    Case TURNOVER = 'tsum_cf_turnover';
}
