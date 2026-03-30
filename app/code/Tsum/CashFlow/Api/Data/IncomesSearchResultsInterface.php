<?php

namespace Tsum\CashFlow\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface IncomesSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Tsum\CashFlow\Api\Data\IncomesInterface[]
     */
    public function getItems();

    /**
     * @param \Tsum\CashFlow\Api\Data\IncomesInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
