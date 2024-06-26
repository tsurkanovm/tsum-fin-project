<?php

namespace Tsum\CashFlowImport\Model\Pryvat\Mapping;

use Magento\Framework\DataObject;
use Tsum\CashFlowImport\Model\RowDocument;

class Transfer extends AbstractDocumentMap
{
    public function map(RowDocument $documentData): void
    {
        $stage = $this->createStagingModel($documentData);
        $this->saveStagingModel($stage);
    }
}
