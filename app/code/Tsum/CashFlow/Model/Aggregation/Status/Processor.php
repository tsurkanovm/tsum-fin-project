<?php

namespace Tsum\CashFlow\Model\Aggregation\Status;

use Magento\Framework\FlagManager;

class Processor
{
    public const string FLAG_NAME = 'tsum_cashflow_aggregation_status';
    public function __construct(
        private readonly FlagManager $flagManager,
        private readonly StatusListFactory $statusListFactory
    ) {
    }
    public function process(string $date, bool $withTurnovers = false): void
    {
        $statusString = $this->flagManager->getFlagData(self::FLAG_NAME);
        if ($statusString) {
            /** @var StatusList $statusListClass */
            $statusListClass = unserialize($statusString);
        } else {
            $statusListClass = $this->statusListFactory->create();
        }

        $statusListClass->remains = $date;
        if ($withTurnovers) {
            $statusListClass->turnovers = $date;
        }

        $newStatusString = serialize($statusListClass);
        if ($newStatusString <> $statusString) { // todo - test is it working??
            $this->flagManager->saveFlag(self::FLAG_NAME, $newStatusString);
        }
    }
}
