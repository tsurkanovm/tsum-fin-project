<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Api;

interface ImportFromFileInterface
{
    /**
     * @throw LocalizedException
     */
    public function import(string $file): void;
}
