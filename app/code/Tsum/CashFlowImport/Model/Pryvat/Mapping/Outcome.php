<?php

namespace Tsum\CashFlowImport\Model\Pryvat\Mapping;

use Magento\Framework\DataObject;
use Tsum\CashFlow\Api\Data\CfItemInterface;
use Tsum\CashFlowImport\Model\RowDocument;

class Outcome extends AbstractDocumentMap
{
    public const GLOSHERY_OUT_ID = 13;
    public const TRANSPORT_OUT_ID = 17;
    public const LEARNING_OUT_ID = 14;
    public const UTILITIES_OUT_ID = 29;
    public const CHARITY_OUT_ID = 43;
    public const MEDICAL_OUT_ID = 31;

    public const DICTIONARY = [
        self::GLOSHERY_OUT_ID => [
            'Велика Кишеня',
            'Ашан',
            'VARUS',
            'Сільпо',
            'АТБ',
            'СВIЖЕ МЯСО',
            '176 PIZZA DAY',
            'Магазин продуктов Колбасна Родина',
            'My Water Shop',
            'Coffee mouse',
            'MAHAZYN MIASA RUBKA, KYIV',
            'Kovbasna Liniia, m. KYIV',
            'Фора',
        ],
        self::TRANSPORT_OUT_ID => [
            'Київський метрополітен',
        ],
        self::LEARNING_OUT_ID => [
            'CHATGPT',
            'OPENAI',
            'UDEMY',
        ],
        self::UTILITIES_OUT_ID => [
            'Oplata komunal',
            'VEGA',
            'Комунальні платежі',
            'UTILITIESV_APGP',
        ],
        self::CHARITY_OUT_ID => [
            'Броніцька',
            'Климчук',
            'Кучмієв',
            'Міжнародний рух єдності',
        ],
        self::MEDICAL_OUT_ID => [
            'АПТЕКА',
            'Аптека',
            'TOV GHARMONIJA ZDOROVJA',
        ]
    ];

    public function map(RowDocument $documentData): void
    {
        $stage = $this->createStagingModel($documentData);
        $stage->setTypeId(CfItemInterface::MOVE_OUT_ID);
        $stage->setCfItemId(
            (int)$this->mapCfItemByCommentary($documentData->getCommentary())
        );

        $this->saveStagingModel($stage);
    }
}
