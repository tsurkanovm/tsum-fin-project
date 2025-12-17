<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Aggregation\Status;

use Magento\Framework\FlagManager;

class Processor
{
    public const string FLAG_NAME = 'tsum_cashflow_aggregation_status';

    private const string KEY_REMAINS = 'remains';
    private const string KEY_TURNOVERS = 'turnovers';

    public function __construct(
        private readonly FlagManager       $flagManager,
        private readonly StatusListFactory $statusListFactory,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function process(string $date, bool $withTurnovers = false): void
    {
        $currentFlagJson = $this->flagManager->getFlagData(self::FLAG_NAME);

        $statusList = $this->statusListFactory->create();
        $this->hydrateFromFlag($statusList, $currentFlagJson);

        $statusList->remains = $date;
        if ($withTurnovers) {
            $statusList->turnovers = $date;
        }

        $newFlagJson = json_encode(
            [
                self::KEY_REMAINS => $statusList->remains,
                self::KEY_TURNOVERS => $statusList->turnovers,
            ],
            JSON_THROW_ON_ERROR
        );

        if ($newFlagJson !== $currentFlagJson) {
            $this->flagManager->saveFlag(self::FLAG_NAME, $newFlagJson);
        }
    }

    /**
     * @throws \JsonException
     */
    private function hydrateFromFlag(StatusList $statusList, mixed $flagValue): void
    {
        if (!is_string($flagValue) || $flagValue === '') {
            return;
        }

        $data = json_decode($flagValue, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($data)) {
            return;
        }

        $remains = $data[self::KEY_REMAINS] ?? null;
        if (is_string($remains)) {
            $statusList->remains = $remains;
        }

        $turnovers = $data[self::KEY_TURNOVERS] ?? null;
        if (is_array($turnovers)) {
            $statusList->turnovers = $turnovers;
        }
    }
}
