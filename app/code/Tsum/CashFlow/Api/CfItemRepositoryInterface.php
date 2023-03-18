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
     * @throws CouldNotSaveException
     */
    public function save(CfItemInterface $cfItem): CfItemInterface;

    /**
     * @throws NoSuchEntityException
     */
    public function getById(int|string $cfItemId): CfItemInterface;

    public function getIdByOnesId(int $onesId): ?int;

    public function getList(SearchCriteriaInterface $searchCriteria): CfItemSearchResultsInterface;

    /**
     * @throws CouldNotDeleteException
     */
    public function delete(CfItemInterface $cfItem): bool;

    /**
     * @throws NoSuchEntityException|CouldNotDeleteException
     */
    public function deleteById(int|string $cfItemId): bool;
}
