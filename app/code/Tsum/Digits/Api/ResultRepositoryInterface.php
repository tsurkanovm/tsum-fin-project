<?php

namespace Tsum\Digits\Api;

use Magento\Framework\Api\Search\SearchResultFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Tsum\Digits\Api\Data\ResultInterface;

/**
 * Digits result CRUD interface.
 * @api
 */
interface ResultRepositoryInterface
{
    /**
     * Save result.
     *
     * @param ResultInterface $result
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function save($result);

    /**
     * Retrieve results matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResult
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Retrieve three the best results.
     *
     * @return ResultInterface[]
     * @throws LocalizedException
     */
    public function getThreeVeryBest();

    /**
     * Retrieve the last results for user.
     *
     * @param string|null customerId
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function getLastUserResult(string $customerId);
}
