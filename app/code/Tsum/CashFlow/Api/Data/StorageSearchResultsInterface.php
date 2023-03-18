<?php

namespace Tsum\CashFlow\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface StorageSearchResultsInterface extends SearchResultsInterface
{
    /**
     *
     * @return \Tsum\CashFlow\Api\Data\StorageInterface[]
     */
    public function getItems();

    /**
     *
     * @param \Tsum\CashFlow\Api\Data\StorageInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
