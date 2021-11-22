<?php

namespace Tsum\CashFlow\Model\Indexer;

use Magento\Framework\Indexer\ActionInterface;
use Tsum\CashFlow\Model\Indexer\RemainsAggregation\Full;
use Tsum\CashFlow\Model\Indexer\RemainsAggregation\Partial;

class RemainsAggregation implements ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    const INDEXER_ID = 'tsum_remains_aggregate';

    /**
     * @var Full
     */
    private $indexerFull;

    /**
     * @var Partial
     */
    private $indexerPartial;

    public function __construct(
        Full $indexerFull,
        Partial $indexerPartial
    ) {
        $this->indexerFull = $indexerFull;
        $this->indexerPartial = $indexerPartial;
    }

    /**
     * {@inheritDoc}
     */
    public function executeFull()
    {
        $this->indexerFull->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function executeList(array $ids)
    {
        $this->indexerPartial->executeList($ids);
    }

    /**
     * {@inheritDoc}
     */
    public function executeRow($id)
    {
        $this->indexerPartial->executeList([$id]);
    }

    /**
     * {@inheritDoc}
     */
    public function execute($ids)
    {
        $this->indexerPartial->executeList($ids);
    }
}
