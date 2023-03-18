<?php

namespace Tsum\CashFlow\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Tsum\CashFlow\Api\Data\IncomesInterface;
use Tsum\CashFlow\Api\Data\IncomesSearchResultsInterface;
use Tsum\CashFlow\Helper\Config;
use Tsum\CashFlow\Model\ResourceModel\Incomes\CollectionFactory;
use Tsum\CashFlow\Model\ResourceModel\Incomes\Collection;
use Tsum\CashFlow\Model\IncomesFactory;
use Tsum\CashFlow\Model\ResourceModel\Incomes as ResourceIncomes;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Tsum\CashFlow\Api\IncomesRepositoryInterface;

class IncomesRepository implements IncomesRepositoryInterface
{
    public function __construct(
        private readonly ResourceIncomes $resource,
        private readonly IncomesFactory $incomesFactory,
        private readonly CollectionFactory $incomesCollectionFactory,
        private readonly IncomesSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessorInterface $collectionProcessor
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(IncomesInterface $incomes): IncomesInterface
    {
        try {
            $this->resource->save($incomes);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the incomes: %1', $exception->getMessage()),
                $exception
            );
        }

        return $incomes;
    }

    /**
     * @inheritDoc
     */
    public function getById(int|string $incomesId): IncomesInterface
    {
        $incomes = $this->incomesFactory->create();
        $this->resource->load($incomes, $incomesId);
        if (!$incomes->getId()) {
            throw new NoSuchEntityException(__('The CashFlow incomes with the "%1" ID doesn\'t exist.', $incomesId));
        }

        return $incomes;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getList(SearchCriteriaInterface $searchCriteria): IncomesSearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->incomesCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var IncomesSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(IncomesInterface $incomes): bool
    {
        try {
            $this->resource->delete($incomes);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the incomes: %1',
                $exception->getMessage()
            ));
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int|string $incomesId): bool
    {
        return $this->delete($this->getById($incomesId));
    }
}
