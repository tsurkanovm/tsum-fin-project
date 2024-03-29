<?php

namespace Tsum\CashFlow\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Tsum\CashFlow\Api\Data\StorageInterface;
use Tsum\CashFlow\Api\Data\StorageSearchResultsInterface;
use Tsum\CashFlow\Api\Data\StorageSearchResultsInterfaceFactory;
use Tsum\CashFlow\Api\StorageRepositoryInterface;
use Tsum\CashFlow\Model\ResourceModel\Storage as ResourceStorage;
use Tsum\CashFlow\Model\ResourceModel\Storage\Collection;
use Tsum\CashFlow\Model\ResourceModel\Storage\CollectionFactory;
use Tsum\CashFlow\Model\StorageFactory;

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
        $this->resource->load($storage, $storageId);
        if (!$storage->getId()) {
            throw new NoSuchEntityException(__('The CashFlow storage with the "%1" ID doesn\'t exist.', $storageId));
        }

        return $storage;
    }

    public function getIdByOnesId(int $onesId): ?int
    {
        $storage = $this->storageFactory->create();
        $this->resource->load($storage, $onesId, Config::ONES_CODE_FIELD);

        return $storage->getId();
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
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
