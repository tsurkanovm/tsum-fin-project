<?php

namespace Tsum\CashFlow\Model\Aggregation\Status;

use Magento\Framework\FlagManager;
// todo - probably we dont need this class, delete?
class Processor
{

    public function __construct(
        private readonly StatusListManager $statusListManager,
    ) {
    }
    public function process(string $date, bool $withTurnovers = false): void
    {

        $this->statusListManager->update($date, $withTurnovers ? $date : null);
    }
}
