<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tsum\CashFlow\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for cms page search results.
 * @api
 * @since 100.0.2
 */
interface CfItemSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get pages list.
     *
     * @return \Tsum\CashFlow\Api\Data\CfItemInterface[]
     */
    public function getItems();

    /**
     * Set pages list.
     *
     * @param \Tsum\CashFlow\Api\Data\CfItemInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
