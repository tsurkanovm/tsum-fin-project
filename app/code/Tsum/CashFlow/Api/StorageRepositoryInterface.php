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
     * @param \Tsum\CashFlow\Api\Data\StorageInterface $storage
     * @return \Tsum\CashFlow\Api\Data\StorageInterface
     * @throws CouldNotSaveException
     */
    public function save(StorageInterface $storage): StorageInterface;

    /**
     * @param int|string $storageId
     * @return \Tsum\CashFlow\Api\Data\StorageInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $storageId): StorageInterface;

    /**
     * @param int $onesId
     * @return int|null
     */
    public function getIdByOnesId(int $onesId): ?int;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Tsum\CashFlow\Api\Data\StorageSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): StorageSearchResultsInterface;

    /**
     * @return \Tsum\CashFlow\Api\Data\StorageSearchResultsInterface
     */
    public function getAll(): StorageSearchResultsInterface;

    /**
     * @param \Tsum\CashFlow\Api\Data\StorageInterface $storage
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(StorageInterface $storage): bool;

    /**
     * @param int|string $storageId
     * @return bool
     * @throws NoSuchEntityException|CouldNotDeleteException
     */
    public function deleteById(int $storageId): bool;
}
