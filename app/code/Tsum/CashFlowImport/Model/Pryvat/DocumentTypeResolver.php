<?php

namespace Tsum\CashFlowImport\Model\Pryvat;

use Magento\Framework\Exception\CouldNotSaveException;
use Tsum\CashFlowImport\Model\Pryvat\Mapping\Income;
use Tsum\CashFlowImport\Model\Pryvat\Mapping\Outcome;
use Tsum\CashFlowImport\Model\Pryvat\Mapping\Transfer;
use Tsum\CashFlowImport\Model\RowDocument;

class DocumentTypeResolver
{
    public function __construct(
        private readonly Income $incomeMapper,
        private readonly Outcome $outcomeMapper,
        private readonly Transfer $transferMapper
    ) {
    }

    /**
     * @throws CouldNotSaveException
     */
    public function resolve(RowDocument $documentData): void
    {
        if ($documentData->getTotal() > 0
            && $this->incomeMapper->mapCfItemByCommentary($documentData->getCommentary())) {
            $this->incomeMapper->map($documentData);
            return;
        }

        if ($documentData->getCategory() === Transfer::TRANSFER_CATEGORY) {
            $this->transferMapper->map($documentData);
            return;
        }

        $this->outcomeMapper->map($documentData);
    }
}
