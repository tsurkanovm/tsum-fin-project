<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tsum\CashFlow\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for the storage search results.
 * @api
 * @since 100.0.2
 */
interface StorageSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get pages list.
     *
     * @return \Tsum\CashFlow\Api\Data\StorageInterface[]
     */
    public function getItems();

    /**
     * Set pages list.
     *
     * @param \Tsum\CashFlow\Api\Data\StorageInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
