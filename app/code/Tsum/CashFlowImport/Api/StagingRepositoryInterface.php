<?php

namespace Tsum\CashFlowImport\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Tsum\CashFlowImport\Api\Data\StagingInterface;
use Tsum\CashFlowImport\Api\Data\StagingSearchResultsInterface;

interface StagingRepositoryInterface
{
    /**
     * @throws CouldNotSaveException
     */
    public function save(StagingInterface $staging): StagingInterface;

    /**
     * @throws NoSuchEntityException
     */
    public function getById(int|string $stagingId): StagingInterface;

    public function getList(SearchCriteriaInterface $searchCriteria): StagingSearchResultsInterface;

    /**
     * @throws CouldNotDeleteException
     */
    public function delete(StagingInterface $staging): bool;

    /**
     * @throws NoSuchEntityException|CouldNotDeleteException
     */
    public function deleteById(int|string $stagingId): bool;

    public function isEmpty(): bool;
}
