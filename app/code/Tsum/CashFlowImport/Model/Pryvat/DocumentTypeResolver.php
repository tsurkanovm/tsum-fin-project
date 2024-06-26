<?php

namespace Tsum\CashFlowImport\Model\Pryvat;

use Magento\Framework\DataObject;
use Tsum\CashFlowImport\Model\Pryvat\Mapping\AbstractDocumentMap;
use Tsum\CashFlowImport\Model\Pryvat\Mapping\Dictionary;
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

    public function resolve(RowDocument $documentData): void
    {
        if ($documentData->getTotal() > 0
            && $this->incomeMapper->mapCfItemByCommentary(
                Dictionary::INCOME_COMMENTARY,
                $documentData->getCommentary()
            )
        ) {
            $this->incomeMapper->map($documentData);
            return;
        }

        if ($documentData->getCategory() === Dictionary::TRANSFER_CATEGORY) {
            $this->transferMapper->map($documentData);
            return;
        }

        $this->outcomeMapper->map($documentData);
    }
}
