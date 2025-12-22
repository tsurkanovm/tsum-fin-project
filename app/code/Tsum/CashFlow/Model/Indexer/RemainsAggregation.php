<?php

namespace Tsum\CashFlow\Model\Indexer;

use Magento\Framework\Indexer\ActionInterface;
use Tsum\CashFlow\Model\Aggregation\Calculator;

class RemainsAggregation implements ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    const INDEXER_ID = 'tsum_remains_aggregate';

    public function __construct(private readonly Calculator $processor) {
    }

    /**
     * {@inheritDoc}
     */
    public function executeFull()
    {
        $this->processor->fullProcessing();
    }

    /**
     * {@inheritDoc}
     */
    public function executeList(array $ids)
    {
        $startDate = $this->getStartDate($ids);

        $this->processor->byDateProcessing($startDate);
    }

    /**
     * {@inheritDoc}
     */
    public function executeRow($id)
    {
        $this->processor->byDateProcessing($id);
    }

    /**
     * {@inheritDoc}
     */
    public function execute($ids)
    {
        $startDate = $this->getStartDate($ids);

        $this->processor->byDateProcessing($startDate);
    }

    private function getStartDate(array $ids): string
    {
        return min($ids);
    }
}
