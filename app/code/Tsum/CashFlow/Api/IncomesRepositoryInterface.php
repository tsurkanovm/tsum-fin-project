<?php

namespace Tsum\CashFlow\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Tsum\CashFlow\Api\Data\IncomesInterface;
use Tsum\CashFlow\Api\Data\IncomesSearchResultsInterface;

interface IncomesRepositoryInterface
{
    /**
     * @param \Tsum\CashFlow\Api\Data\IncomesInterface $incomes
     * @return \Tsum\CashFlow\Api\Data\IncomesInterface
     * @throws CouldNotSaveException
     */
    public function save(IncomesInterface $incomes): IncomesInterface;

    /**
     * @param int|string $incomesId
     * @return \Tsum\CashFlow\Api\Data\IncomesInterface
     * @throws NoSuchEntityException
     */
    public function getById(int|string $incomesId): IncomesInterface;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Tsum\CashFlow\Api\Data\IncomesSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): IncomesSearchResultsInterface;

    /**
     * @return \Tsum\CashFlow\Api\Data\IncomesSearchResultsInterface
     */
    public function getAll(): IncomesSearchResultsInterface;

    /**
     * @param \Tsum\CashFlow\Api\Data\IncomesInterface $incomes
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(IncomesInterface $incomes): bool;

    /**
     * @param int|string $incomesId
     * @return bool
     * @throws NoSuchEntityException|CouldNotDeleteException
     */
    public function deleteById(int|string $incomesId): bool;
}
