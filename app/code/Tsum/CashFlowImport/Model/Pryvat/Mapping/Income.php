<?php

namespace Tsum\CashFlowImport\Model\Pryvat\Mapping;

use Magento\Framework\Exception\CouldNotSaveException;
use Tsum\CashFlow\Api\Data\CfItemInterface;
use Tsum\CashFlowImport\Model\RowDocument;

class Income extends AbstractDocumentMap
{
    public const RENT_IN_ID = 19;
    public const DEFAULT_CF_ID = 7 ;

    public const DICTIONARY = [
        self::RENT_IN_ID => [
            'Olena Petrova',
            'Іваненко',
        ]
    ];

    public function map(RowDocument $documentData): void
    {
        $stage = $this->createStagingModel($documentData);
        $stage->setTypeId(CfItemInterface::MOVE_IN_ID);
        $stage->setCfItemId(
            (int)$this->mapCfItemByCommentary($documentData->getCommentary())
        );

        $this->saveStagingModel($stage);
    }
}
