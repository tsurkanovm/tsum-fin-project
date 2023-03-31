<?php

namespace Tsum\CashFlow\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Tsum\CashFlow\Api\Data\TransferInterface;
use Tsum\CashFlow\Api\Data\TransferSearchResultsInterface;

interface TransferRepositoryInterface
{
    /**
     * @throws CouldNotSaveException
     */
    public function save(TransferInterface $transfer): TransferInterface;

    /**
     * @throws NoSuchEntityException
     */
    public function getById(int|string $transferId): TransferInterface;


    public function getList(SearchCriteriaInterface $searchCriteria): TransferSearchResultsInterface;

    /**
     * @throws CouldNotDeleteException
     */
    public function delete(TransferInterface $transfer): bool;

    /**
     * @throws NoSuchEntityException|CouldNotDeleteException
     */
    public function deleteById(int|string $transferId): bool;
}
