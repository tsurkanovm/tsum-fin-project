<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tsum\CashFlow\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * CMS page CRUD interface.
 * @api
 * @since 100.0.2
 */
interface StorageRepositoryInterface
{
    /**
     * Save page.
     *
     * @param \Tsum\CashFlow\Api\Data\StorageInterface $storage
     * @return \Tsum\CashFlow\Api\Data\StorageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Tsum\CashFlow\Api\Data\StorageInterface $storage);

    /**
     * Retrieve page.
     *
     * @param int $storageId
     * @return \Tsum\CashFlow\Api\Data\StorageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($storageId);

    /**
     * Retrieve pages matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Tsum\CashFlow\Api\Data\StorageSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete page.
     *
     * @param \Tsum\CashFlow\Api\Data\StorageInterface $storage
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Tsum\CashFlow\Api\Data\StorageInterface $storage);

    /**
     * Delete page by ID.
     *
     * @param int $storageId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($storageId);
}
