<?php

namespace Tsum\CashFlow\Model\Aggregation\Status;

use Magento\Framework\FlagManager;
use Tsum\CashFlow\Api\StatusListManagerInterface;

class StatusListManager implements StatusListManagerInterface
{
    public const string FLAG_NAME = 'tsum_cashflow_aggregation_status';
    public function __construct(
        private readonly FlagManager $flagManager,
        private readonly StatusListFactory $statusListFactory,
        private readonly ValueProvider $valueProvider,
    ) {
    }

    public function get() : StatusList
    {
        $statusString = $this->flagManager->getFlagData(self::FLAG_NAME);
        if ($statusString) {
           return unserialize($statusString);
        } else {
            return $this->statusListFactory->create();
        }
    }

    public function update(string $remains, ?string $turnovers): void
    {
        $statusListClass = $this->get();
        $statusString = serialize($statusListClass);
        $statusListClass->remains = $this->valueProvider->provideRemainValue($remains, $statusListClass->remains);
        if ($turnovers) {
            $statusListClass->turnovers = $this->valueProvider->provideTurnoverValue($turnovers, $statusListClass->turnovers);
        }

        $newStatusString = serialize($statusListClass);
        if ($newStatusString <> $statusString) {
            $this->flagManager->saveFlag(self::FLAG_NAME, $newStatusString);
        }
    }
}
