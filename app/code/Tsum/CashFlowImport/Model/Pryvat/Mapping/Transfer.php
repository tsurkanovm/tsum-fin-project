<?php

namespace Tsum\CashFlowImport\Model\Pryvat\Mapping;

use Tsum\CashFlowImport\Api\Data\StagingInterface;
use Tsum\CashFlowImport\Model\RowDocument;

class Transfer extends AbstractDocumentMap
{
    public const TRANSFER_CATEGORY = 'Перекази';

    public function map(RowDocument $documentData): void
    {
        $stage = $this->createStagingModel($documentData);
        $stage->setTypeId(StagingInterface::TRANSFER_TYPE_ID);
        $this->saveStagingModel($stage);
    }
}
