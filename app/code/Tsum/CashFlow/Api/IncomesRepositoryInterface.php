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
     * @throws CouldNotSaveException
     */
    public function save(IncomesInterface $incomes): IncomesInterface;

    /**
     * @throws NoSuchEntityException
     */
    public function getById(int|string $incomesId): IncomesInterface;


    public function getList(SearchCriteriaInterface $searchCriteria): IncomesSearchResultsInterface;

    /**
     * @throws CouldNotDeleteException
     */
    public function delete(IncomesInterface $incomes): bool;

    /**
     * @throws NoSuchEntityException|CouldNotDeleteException
     */
    public function deleteById(int|string $incomesId): bool;
}
