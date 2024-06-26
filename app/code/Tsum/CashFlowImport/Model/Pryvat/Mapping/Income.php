<?php

namespace Tsum\CashFlowImport\Model\Pryvat\Mapping;

use Tsum\CashFlow\Api\Data\CfItemInterface;
use Tsum\CashFlowImport\Model\RowDocument;

class Income extends AbstractDocumentMap
{
    public function map(RowDocument $documentData): void
    {
        $stage = $this->createStagingModel($documentData);
        $stage->setTypeId(CfItemInterface::MOVE_IN_ID);
        $stage->setCfItemId(
            (int)$this->mapCfItemByCommentary(Dictionary::INCOME_COMMENTARY, $documentData->getCommentary())
        );
        $stage->setTotal($documentData->getTotal());

        $this->saveStagingModel($stage);
    }
}
