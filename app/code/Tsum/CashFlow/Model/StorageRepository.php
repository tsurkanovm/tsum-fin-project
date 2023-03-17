<?php

namespace Tsum\CashFlow\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Tsum\CashFlow\Api\Data\StorageInterface;
use Tsum\CashFlow\Api\Data\StorageSearchResultsInterface;
use Tsum\CashFlow\Model\ResourceModel\Storage\CollectionFactory;
use Tsum\CashFlow\Model\ResourceModel\Storage\Collection;
use Tsum\CashFlow\Model\StorageFactory;
use Tsum\CashFlow\Model\ResourceModel\Storage as ResourceStorage;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Tsum\CashFlow\Api\StorageRepositoryInterface;

class StorageRepository implements StorageRepositoryInterface
{
    public function __construct(
        private readonly ResourceStorage $resource,
        private readonly StorageFactory $storageFactory,
        private readonly CollectionFactory $storageCollectionFactory,
        private readonly StorageSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessorInterface $collectionProcessor
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(StorageInterface $storage): StorageInterface
    {
        try {
            $this->resource->save($storage);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the storage: %1', $exception->getMessage()),
                $exception
            );
        }

        return $storage;
    }

    /**
     * @inheritDoc
     */
    public function getById(int|string $storageId): StorageInterface
    {
        $storage = $this->storageFactory->create();
        $storage->load($storageId);
        if (!$storage->getId()) {
            throw new NoSuchEntityException(__('The CashFlow storage with the "%1" ID doesn\'t exist.', $storageId));
        }

        return $storage;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getList(SearchCriteriaInterface $searchCriteria): StorageSearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->storageCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var StorageSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(StorageInterface $storage): bool
    {
        try {
            $this->resource->delete($storage);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the storage: %1',
                $exception->getMessage()
            ));
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int|string $storageId): bool
    {
        return $this->delete($this->getById($storageId));
    }
}
