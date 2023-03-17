<?php

namespace Tsum\CashFlow\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Tsum\CashFlow\Api\Data\CfItemInterface;
use Tsum\CashFlow\Api\Data\CfItemSearchResultsInterface;
use Tsum\CashFlow\Model\ResourceModel\CfItem\CollectionFactory;
use Tsum\CashFlow\Model\ResourceModel\CfItem\Collection;
use Tsum\CashFlow\Model\CfItemFactory;
use Tsum\CashFlow\Model\ResourceModel\CfItem as ResourceCfItem;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Tsum\CashFlow\Api\CfItemRepositoryInterface;

class CfItemRepository implements CfItemRepositoryInterface
{
    public function __construct(
        private readonly ResourceCfItem $resource,
        private readonly CfItemFactory $cfItemFactory,
        private readonly CollectionFactory $cfItemCollectionFactory,
        private readonly CfItemSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessorInterface $collectionProcessor
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(CfItemInterface $cfItem): CfItemInterface
    {
        try {
            $this->resource->save($cfItem);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the cfItem: %1', $exception->getMessage()),
                $exception
            );
        }

        return $cfItem;
    }

    /**
     * @inheritDoc
     */
    public function getById(int|string $cfItemId): CfItemInterface
    {
        $cfItem = $this->cfItemFactory->create();
        $cfItem->load($cfItemId);
        if (!$cfItem->getId()) {
            throw new NoSuchEntityException(__('The CashFlow cfItem with the "%1" ID doesn\'t exist.', $cfItemId));
        }

        return $cfItem;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getList(SearchCriteriaInterface $searchCriteria): CfItemSearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->cfItemCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var CfItemSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(CfItemInterface $cfItem): bool
    {
        try {
            $this->resource->delete($cfItem);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the cfItem: %1',
                $exception->getMessage()
            ));
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int|string $cfItemId): bool
    {
        return $this->delete($this->getById($cfItemId));
    }
}
