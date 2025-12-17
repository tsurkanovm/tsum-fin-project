<?php

namespace Tsum\CashFlow\Model\Aggregation\Status;

use Magento\Framework\FlagManager;
use Tsum\CashFlow\Api\Data\RegistrationDocumentInterface;
use Tsum\CashFlow\Model\Incomes;

class Processor
{

    public function __construct(
        private readonly StatusListManager $statusListManager,
    ) {
    }
    public function process(RegistrationDocumentInterface $registrationDocument, $deleteOperation = false): void
    {
        $date = $registrationDocument->getRegistrationTime();
        $withTurnovers = $registrationDocument instanceof Incomes;
        if ($deleteOperation) {
            if ($registrationDocument->isActive()) {
                $this->statusListManager->update($date, $withTurnovers ? $date : null);
            }
        } else {
            $this->statusListManager->update($date, $withTurnovers ? $date : null);
        }
    }
}
