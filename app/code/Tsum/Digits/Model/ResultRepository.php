<?php

namespace Tsum\Digits\Model;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchResultFactory;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\CouldNotSaveException;
use Tsum\Digits\Api\ResultRepositoryInterface;
use Tsum\Digits\Api\Data\ResultInterface;
use Tsum\Digits\Model\ResourceModel\Result as ResultResource;
use Tsum\Digits\Model\ResourceModel\Result\Collection;
use Tsum\Digits\Model\ResourceModel\Result\CollectionFactory;
use Magento\Framework\Api\Search\SearchCriteriaInterfaceFactory;

class ResultRepository implements ResultRepositoryInterface
{
    public function __construct(
        private readonly ResultResource $resource,
        private readonly CollectionFactory $resultCollectionFactory,
        private readonly SearchCriteriaInterfaceFactory $searchCriteriaInterfaceFactory,
        private readonly CollectionProcessorInterface $collectionProcessor,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly FilterBuilder $filterBuilder,
        private readonly SortOrderBuilder $sortOrderBuilder,
        private readonly SearchResultFactory $searchResultsFactory
    ) {
    }

    public function save(ResultInterface $result): ResultInterface
    {
        try {
            $this->resource->save($result);

            return $result;
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the result: %1', $exception->getMessage()),
                $exception
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $criteria): SearchResultInterface
    {
        $collection = $this->resultCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        // @phpstan-ignore-next-line
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    public function getThreeVeryBest(?int $size = self::DEFAULT_SIZE): array
    {
        $sizeFilter = $this->filterBuilder
            ->setField(ResultInterface::SIZE)
            ->setValue((string)$size)
            ->setConditionType('eq')
            ->create();

        $hitSortOrder = $this->sortOrderBuilder
            ->setField(ResultInterface::HITS)
            ->setAscendingDirection()
            ->create();
        $timeSortOrder = $this->sortOrderBuilder
            ->setField(ResultInterface::TIME)
            ->setAscendingDirection()
            ->create();

        $this->searchCriteriaBuilder->addFilters([$sizeFilter]);
        $this->searchCriteriaBuilder->setSortOrders([$hitSortOrder, $timeSortOrder]);
        $this->searchCriteriaBuilder->setPageSize(3);

        $searchRes = $this->getList($this->searchCriteriaBuilder->create());
        // @phpstan-ignore-next-line
        return $searchRes->getItems();
    }

    public function getLastUserResult(string $customerId): array
    {
        $result = [];

        $filterCurrentUser = $this->filterBuilder
            ->setField(ResultInterface::CUSTOMER_ID)
            ->setValue($customerId)
            ->setConditionType('eq')
            ->create();
        $sortOrder = $this->sortOrderBuilder
            ->setField(ResultInterface::CREATION_TIME)
            ->setDescendingDirection()
            ->create();
        $this->searchCriteriaBuilder->addSortOrder($sortOrder);
        $this->searchCriteriaBuilder->addFilters([$filterCurrentUser]);
        $this->searchCriteriaBuilder->setPageSize(1);

        $searchRes = $this->getList($this->searchCriteriaBuilder->create());
        /* @var $item ResultInterface */
        if ($item = current($searchRes->getItems())) {
            $result = $item->getData();
        }

        return $result;
    }
}
