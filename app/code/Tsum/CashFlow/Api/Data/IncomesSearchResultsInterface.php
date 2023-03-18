<?php

namespace Tsum\CashFlow\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface IncomesSearchResultsInterface extends SearchResultsInterface
{
    public function getItems();

    public function setItems(array $items);
}
