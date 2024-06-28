<?php

namespace Tsum\CashFlowImport\Model\Pryvat\Mapping;

use Magento\Framework\Exception\CouldNotSaveException;
use Tsum\CashFlowImport\Api\StagingRepositoryInterface;
use Tsum\CashFlowImport\Model\RowDocument;
use Tsum\CashFlowImport\Model\Staging;
use Tsum\CashFlowImport\Model\StagingFactory;

abstract class AbstractDocumentMap
{
    // by default for out CF_id - others
    public const DEFAULT_CF_ID = 9;

    public const DICTIONARY = []; // will be defined in children
    public const DEFAULT_CURRENCY = 'UAH';
    public function __construct(
        private readonly StagingFactory $stagingFactory,
        private readonly StagingRepositoryInterface $stagingRepository,
    ) {
    }

    /**
     * @throws CouldNotSaveException
     */
    abstract public function map(RowDocument $documentData): void;

    protected function createStagingModel(RowDocument $documentData): Staging
    {
        $stage = $this->stagingFactory->create();
        $stage->setRegistrationTime($documentData->getRegistrationTime());
        $stage->setStorageId($documentData->getStorageId());
        $stage->setCommentary($documentData->getCategory() . ' | ' . $documentData->getCommentary());
        $stage->setCurrency(self::DEFAULT_CURRENCY);
        $stage->setTotal($documentData->getTotal() > 0 ? $documentData->getTotal() : -$documentData->getTotal());

        return $stage;
    }

    /**
     * @throws CouldNotSaveException
     */
    protected function saveStagingModel(Staging $stagingModel): void
    {
        $this->stagingRepository->save($stagingModel);
    }

    public function mapCfItemByCommentary(string $commentary): int|false
    {
        foreach (static::DICTIONARY as $id => $commentaries) {
            foreach ($commentaries as $dictCommentary) {
                if ($this->isMatch($dictCommentary, $commentary)) {
                    return $id;
                }
            }
        }

        return static::DEFAULT_CF_ID;
    }

    /**
     * Check if the dictionary commentary matches the given commentary.
     *
     * @param string $dictCommentary The commentary from the dictionary.
     * @param string $commentary The actual commentary to check.
     * @return bool True if the commentary matches, false otherwise.
     */
    private function isMatch(string $dictCommentary, string $commentary): bool
    {
        return str_starts_with($commentary, $dictCommentary);
    }
}
