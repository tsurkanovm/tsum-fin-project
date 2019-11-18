<?php

namespace Tsum\CashFlow\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Tsum\CashFlow\Api\Data\StorageInterfaceFactory;
use Tsum\CashFlow\Api\Data;
use Tsum\CashFlow\Api\Data\StorageSearchResultsInterface;
use Tsum\CashFlow\Model\ResourceModel\Storage\CollectionFactory;
use Tsum\CashFlow\Model\ResourceModel\Storage\Collection;
use Tsum\CashFlow\Model\StorageFactory;
use Tsum\CashFlow\Model\ResourceModel\Storage as ResourceStorage;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Tsum\CashFlow\Api\StorageRepositoryInterface;

class StorageRepository implements StorageRepositoryInterface
{
    /**
     * @var ResourceStorage
     */
    protected $resource;

    /**
     * @var StorageFactory
     */
    protected $storageFactory;

    /**
     * @var CollectionFactory
     */
    protected $storageCollectionFactory;

    /**
     * @var Data\StorageSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var StorageInterfaceFactory
     */
    protected $dataStorageFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * StorageRepository constructor.
     * @param ResourceStorage $resource
     * @param StorageFactory $storageFactory
     * @param CollectionFactory $storageCollectionFactory
     * @param Data\StorageSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StorageInterfaceFactory $dataStorageFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceStorage $resource,
        StorageFactory $storageFactory,
        CollectionFactory $storageCollectionFactory,
        Data\StorageSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StorageInterfaceFactory $dataStorageFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->storageFactory = $storageFactory;
        $this->storageCollectionFactory = $storageCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->dataStorageFactory = $dataStorageFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Save Storage data
     *
     * @param Data\StorageInterface $storage
     * @return Data\StorageInterface
     * @throws CouldNotSaveException
     */
    public function save(Data\StorageInterface $storage)
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
     * Load Storage data by given Storage Identity
     *
     * @param int $storageId
     * @return Storage
     * @throws NoSuchEntityException
     */
    public function getById($storageId)
    {
        $storage = $this->storageFactory->create();
        $storage->load($storageId);
        if (!$storage->getId()) {
            throw new NoSuchEntityException(__('The CashFlow storage with the "%1" ID doesn\'t exist.', $storageId));
        }

        return $storage;
    }

    /**
     * Load Storage data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param SearchCriteriaInterface $criteria
     * @return StorageSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var Collection $collection */
        $collection = $this->storageCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var StorageSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * Delete Storage
     *
     * @param Data\StorageInterface $storage
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\StorageInterface $storage)
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
     * Delete Storage by given Page Identity
     *
     * @param int $storageId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($storageId)
    {
        return $this->delete($this->getById($storageId));
    }
}
