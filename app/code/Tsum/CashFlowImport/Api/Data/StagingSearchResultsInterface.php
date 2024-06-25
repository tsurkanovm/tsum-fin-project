<?php

namespace Tsum\CashFlowImport\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface StagingSearchResultsInterface extends SearchResultsInterface
{
    public function getItems();

    public function setItems(array $items);
}
