<?php

namespace Tsum\Digits\Api;

use Magento\Framework\Api\Search\SearchResultFactory;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Tsum\Digits\Api\Data\ResultInterface;

/**
 * Digits result CRUD interface.
 * @api
 */
interface ResultRepositoryInterface
{
    public const DEFAULT_SIZE = 4;
    /**
     * Save result.
     *
     * @param ResultInterface $result
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function save(ResultInterface $result): ResultInterface;

    /**
     * Retrieve results matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultInterface;

    /**
     * Retrieve three the best results.
     *
     * @return ResultInterface[]
     * @throws LocalizedException
     */
    public function getThreeVeryBest(?int $size = self::DEFAULT_SIZE): array;

    /**
     * Retrieve the last results for user.
     *
     * @param string $customerId
     * @return mixed[]
     * @throws LocalizedException
     */
    public function getLastUserResult(string $customerId): array;
}
