<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Import;

class TransferFormat
{
    public const REGISTRATION_ROW = 0;
    public const CURRENCY_ROW = 1;
    public const STORAGE_ID_ROW = 2;
    public const IN_CURRENCY_ROW = 3;
    public const IN_STORAGE_ID_ROW = 4;
    public const TOTAL_ROW = 5;
    public const IN_TOTAL_ROW = 6;
}
