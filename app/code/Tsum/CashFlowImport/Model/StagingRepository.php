<?php

namespace Tsum\CashFlowImport\Model;

use Magento\Framework\Api\Search\SearchResult;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Tsum\CashFlowImport\Api\Data\StagingInterface;
use Tsum\CashFlowImport\Api\Data\StagingSearchResultsInterface;
use Tsum\CashFlowImport\Api\Data\StagingSearchResultsInterfaceFactory;
use Tsum\CashFlowImport\Api\StagingRepositoryInterface;
use Tsum\CashFlowImport\Model\StagingFactory;
use Tsum\CashFlowImport\Model\ResourceModel\Staging as ResourceIncomes;
use Tsum\CashFlowImport\Model\ResourceModel\Staging\Collection;
use Tsum\CashFlowImport\Model\ResourceModel\Staging\CollectionFactory;

class StagingRepository implements StagingRepositoryInterface
{
    public function __construct(
        private readonly ResourceIncomes $resource,
        private readonly StagingFactory $stagingFactory,
        private readonly CollectionFactory $stagingCollectionFactory,
        private readonly StagingSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessorInterface $collectionProcessor
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(StagingInterface $staging): StagingInterface
    {
        try {
            $this->resource->save($staging);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the staging: %1', $exception->getMessage()),
                $exception
            );
        }

        return $staging;
    }

    /**
     * @inheritDoc
     * @throws NoSuchEntityException
     */
    public function getById(int|string $stagingId): StagingInterface
    {
        $staging = $this->stagingFactory->create();
        $this->resource->load($staging, $stagingId);
        if (!$staging->getId()) {
            throw new NoSuchEntityException(__('The CashFlow staging with the "%1" ID doesn\'t exist.', $stagingId));
        }

        return $staging;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResults
    {
        /** @var Collection $collection */
        $collection = $this->stagingCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        /** @phpstan-ignore-next-line */
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        /** @phpstan-ignore-next-line */
        return $searchResults;
    }

    /**
     * @inheritDoc
     * @throws CouldNotDeleteException
     */
    public function delete(StagingInterface $staging): bool
    {
        try {
            $this->resource->delete($staging);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the staging: %1',
                $exception->getMessage()
            ));
        }

        return true;
    }

    /**
     * @inheritDoc
     * @throws NoSuchEntityException|CouldNotDeleteException
     */
    public function deleteById(int|string $stagingId): bool
    {
        return $this->delete($this->getById($stagingId));
    }

    public function isEmpty(): bool
    {
        /** @var Collection $collection */
        $collection = $this->stagingCollectionFactory->create();

        return (bool)$collection->getSize();
    }
}
