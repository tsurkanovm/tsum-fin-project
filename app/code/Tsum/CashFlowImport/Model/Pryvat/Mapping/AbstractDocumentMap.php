<?php

namespace Tsum\CashFlowImport\Model\Pryvat\Mapping;

use Magento\Framework\Exception\CouldNotSaveException;
use Tsum\CashFlowImport\Api\StagingRepositoryInterface;
use Tsum\CashFlowImport\Model\RowDocument;
use Tsum\CashFlowImport\Model\Staging;
use Tsum\CashFlowImport\Model\StagingFactory;

abstract class AbstractDocumentMap
{
    public const DEFAULT_CURRENCY = 'UAH';
    public function __construct(
        private readonly StagingFactory $stagingFactory,
        private readonly StagingRepositoryInterface $stagingRepository,
    ) {
    }

    abstract public function map(RowDocument $documentData): void;

    protected function createStagingModel(RowDocument $documentData): Staging
    {
        $stage = $this->stagingFactory->create();
        $stage->setRegistrationTime($documentData->getRegistrationTime());
        $stage->setStorageId($documentData->getStorageId());
        $stage->setCommentary($documentData->getCategory() . ' | ' . $documentData->getCommentary());
        $stage->setCurrency(self::DEFAULT_CURRENCY);

        return $stage;
    }

    /**
     * @throws CouldNotSaveException
     */
    protected function saveStagingModel(Staging $stagingModel): void
    {
        $this->stagingRepository->save($stagingModel);
    }

    /**
     * Map commentary to its corresponding CF Item ID.
     *
     * @param array<int, array<string>> $dictionary
     * @param string $commentary The commentary to search for.
     * @return int|false The corresponding ID if found, false otherwise.
     */
    public function mapCfItemByCommentary(array $dictionary, string $commentary): int|false
    {
        foreach ($dictionary as $id => $commentaries) {
            if (in_array($commentary, $commentaries, true)) {
                return $id;
            }
        }

        return false;
    }
}
