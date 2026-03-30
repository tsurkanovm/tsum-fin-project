<?php

namespace Tsum\CashFlow\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Tsum\CashFlow\Api\Data\CfItemInterface;
use Tsum\CashFlow\Api\Data\CfItemSearchResultsInterface;

interface CfItemRepositoryInterface
{
    /**
     * @param \Tsum\CashFlow\Api\Data\CfItemInterface $cfItem
     * @return \Tsum\CashFlow\Api\Data\CfItemInterface
     * @throws CouldNotSaveException
     */
    public function save(CfItemInterface $cfItem): CfItemInterface;

    /**
     * @param int|string $cfItemId
     * @return \Tsum\CashFlow\Api\Data\CfItemInterface
     * @throws NoSuchEntityException
     */
    public function getById(int|string $cfItemId): CfItemInterface;

    /**
     * @param int $onesId
     * @return int|null
     */
    public function getIdByOnesId(int $onesId): ?int;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Tsum\CashFlow\Api\Data\CfItemSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): CfItemSearchResultsInterface;

    /**
     * @return \Tsum\CashFlow\Api\Data\CfItemSearchResultsInterface
     */
    public function getAll(): CfItemSearchResultsInterface;

    /**
     * @param \Tsum\CashFlow\Api\Data\CfItemInterface $cfItem
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(CfItemInterface $cfItem): bool;

    /**
     * @param int|string $cfItemId
     * @return bool
     * @throws NoSuchEntityException|CouldNotDeleteException
     */
    public function deleteById(int|string $cfItemId): bool;
}
