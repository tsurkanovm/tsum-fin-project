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
     * @param \Tsum\CashFlow\Api\Data\TransferInterface $transfer
     * @return \Tsum\CashFlow\Api\Data\TransferInterface
     * @throws CouldNotSaveException
     */
    public function save(TransferInterface $transfer): TransferInterface;

    /**
     * @param int|string $transferId
     * @return \Tsum\CashFlow\Api\Data\TransferInterface
     * @throws NoSuchEntityException
     */
    public function getById(int|string $transferId): TransferInterface;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Tsum\CashFlow\Api\Data\TransferSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): TransferSearchResultsInterface;

    /**
     * @return \Tsum\CashFlow\Api\Data\TransferSearchResultsInterface
     */
    public function getAll(): TransferSearchResultsInterface;

    /**
     * @param \Tsum\CashFlow\Api\Data\TransferInterface $transfer
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(TransferInterface $transfer): bool;

    /**
     * @param int|string $transferId
     * @return bool
     * @throws NoSuchEntityException|CouldNotDeleteException
     */
    public function deleteById(int|string $transferId): bool;
}
