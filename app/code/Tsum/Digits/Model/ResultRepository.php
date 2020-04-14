<?php

namespace Tsum\Digits\Model;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchResultFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Tsum\Digits\Api\ResultRepositoryInterface;
use Tsum\Digits\Api\Data\ResultInterface;
use Tsum\Digits\Model\ResourceModel\Result as ResultResource;
use Tsum\Digits\Model\ResourceModel\Result\Collection;
use Tsum\Digits\Model\ResourceModel\Result\CollectionFactory;
use Magento\Framework\Api\Search\SearchCriteriaInterfaceFactory;

class ResultRepository implements ResultRepositoryInterface
{
    /**
     * @var ResultResource
     */
    private $resource;

    /**
     * @var CollectionFactory
     */
    private $resultCollectionFactory;

    /**
     * @var SearchCriteriaInterfaceFactory
     */
    private $searchCriteriaInterfaceFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @var SearchResultFactory
     */
    private $searchResultsFactory;

    /**
     * ResultRepository constructor.
     * @param ResultResource $resource
     * @param CollectionFactory $resultCollectionFactory
     * @param SearchCriteriaInterfaceFactory $searchCriteriaInterfaceFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param SearchResultFactory $searchResultsFactory
     */
    public function __construct(
        ResultResource $resource,
        CollectionFactory $resultCollectionFactory,
        SearchCriteriaInterfaceFactory $searchCriteriaInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        SortOrderBuilder $sortOrderBuilder,
        SearchResultFactory $searchResultsFactory
    ) {
        $this->resource = $resource;
        $this->resultCollectionFactory = $resultCollectionFactory;
        $this->searchCriteriaInterfaceFactory = $searchCriteriaInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    public function save($result)
    {
        try {
            $this->resource->save($result);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the result: %1', $exception->getMessage()),
                $exception
            );
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var Collection $collection */
        $collection = $this->resultCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    public function getThreeVeryBest()
    {
        $result = null;

        $hitSortOrder = $this->sortOrderBuilder
            ->setField(ResultInterface::HITS)
            ->setDescendingDirection()
            ->create();
        $timeSortOrder = $this->sortOrderBuilder
            ->setField(ResultInterface::TIME)
            ->setDescendingDirection()
            ->create();
        $this->searchCriteriaBuilder->setSortOrders([$hitSortOrder, $timeSortOrder]);
        $this->searchCriteriaBuilder->setPageSize(3);

        if ($searchRes = $this->getList($this->searchCriteriaBuilder->create())) {
            $result = json_encode(array_map(
                function ($item) {
                    return $item->getData();
                },
                $searchRes->getItems()
            ));
        }

        return $result;
    }

    public function getLastUserResult(string $customerId)
    {
        $result = null;

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

        if ($searchRes = $this->getList($this->searchCriteriaBuilder->create())) {
            /* @var $item ResultInterface */
            if ($item = current($searchRes->getItems())) {
                $result = json_encode($item->getData());
            }
        }

        return $result;
    }
}
