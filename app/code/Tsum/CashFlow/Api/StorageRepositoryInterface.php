<?php

namespace Tsum\CashFlow\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Tsum\CashFlow\Api\Data\StorageInterface;
use Tsum\CashFlow\Api\Data\StorageSearchResultsInterface;

interface StorageRepositoryInterface
{
    /**
     * @throws CouldNotSaveException
     */
    public function save(StorageInterface $storage): StorageInterface;

    /**
     * @throws NoSuchEntityException
     */
    public function getById(int|string $storageId): StorageInterface;

    public function getIdByOnesId(int $onesId): ?int;

    public function getList(SearchCriteriaInterface $searchCriteria): StorageSearchResultsInterface;

    /**
     * @throws CouldNotDeleteException
     */
    public function delete(StorageInterface $storage): bool;

    /**
     * @throws NoSuchEntityException|CouldNotDeleteException
     */
    public function deleteById(int|string $storageId): bool;
}
