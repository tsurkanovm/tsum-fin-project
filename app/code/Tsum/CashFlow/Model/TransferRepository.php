<?php

namespace Tsum\CashFlow\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Tsum\CashFlow\Api\Data\TransferInterface;
use Tsum\CashFlow\Api\Data\TransferSearchResultsInterface;
use Tsum\CashFlow\Api\Data\TransferSearchResultsInterfaceFactory;
use Tsum\CashFlow\Api\TransferRepositoryInterface;
use Tsum\CashFlow\Model\TransferFactory;
use Tsum\CashFlow\Model\ResourceModel\Transfer as ResourceTransfer;
use Tsum\CashFlow\Model\ResourceModel\Transfer\Collection;
use Tsum\CashFlow\Model\ResourceModel\Transfer\CollectionFactory;

class TransferRepository implements TransferRepositoryInterface
{
    public function __construct(
        private readonly ResourceTransfer $resource,
        private readonly TransferFactory $transferFactory,
        private readonly CollectionFactory $transferCollectionFactory,
        private readonly TransferSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessorInterface $collectionProcessor
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(TransferInterface $transfer): TransferInterface
    {
        try {
            $this->resource->save($transfer);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the transfer: %1', $exception->getMessage()),
                $exception
            );
        }

        return $transfer;
    }

    /**
     * @inheritDoc
     */
    public function getById(int|string $transferId): TransferInterface
    {
        $transfer = $this->transferFactory->create();
        $this->resource->load($transfer, $transferId);
        if (!$transfer->getId()) {
            throw new NoSuchEntityException(__('The CashFlow transfer with the "%1" ID doesn\'t exist.', $transferId));
        }

        return $transfer;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getList(SearchCriteriaInterface $searchCriteria): TransferSearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->transferCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var TransferSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(TransferInterface $transfer): bool
    {
        try {
            $this->resource->delete($transfer);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the transfer: %1',
                $exception->getMessage()
            ));
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int|string $transferId): bool
    {
        return $this->delete($this->getById($transferId));
    }
}
