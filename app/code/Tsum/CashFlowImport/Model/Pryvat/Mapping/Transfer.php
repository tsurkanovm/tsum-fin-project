<?php

namespace Tsum\CashFlowImport\Model\Pryvat\Mapping;

use Tsum\CashFlowImport\Api\Data\StagingInterface;
use Tsum\CashFlowImport\Model\RowDocument;

class Transfer extends AbstractDocumentMap
{
    public const TRANSFER_CATEGORY = 'Перекази';
    public const VIKA_STORAGE_ID = 37;
    public const CASH_STORAGE_ID = 3;
    public const FOP_STORAGE_ID = 36;

    // uses in parent::mapItemByCommentary
    public const DEFAULT_ITEM_ID = 0;

    public const DICTIONARY = [
        self::VIKA_STORAGE_ID => [
            'Цурканова В.',
        ],
        self::CASH_STORAGE_ID => [
            'Банкомат',
        ],
        self::FOP_STORAGE_ID => [
            'Зарахування',
            'Переказ коштів:'
        ],
    ];

    public function map(RowDocument $documentData): void
    {
        $stage = $this->createStagingModel($documentData);
        $stage->setTypeId(StagingInterface::TRANSFER_TYPE_ID);

        if ($documentData->getTotal() < 0) {
            $stage->setData('storage_id_in', $this->mapItemByCommentary($documentData->getCommentary()));
        } else {
            $stage->setData('storage_id_in', $stage->getStorageId());
            $stage->setStorageId($this->mapItemByCommentary($documentData->getCommentary()));
        }

            $this->saveStagingModel($stage);
    }
}
