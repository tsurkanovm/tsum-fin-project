<?php

namespace Tsum\CashFlow\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface TransferSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Tsum\CashFlow\Api\Data\TransferInterface[]
     */
    public function getItems();

    /**
     * @param \Tsum\CashFlow\Api\Data\TransferInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
