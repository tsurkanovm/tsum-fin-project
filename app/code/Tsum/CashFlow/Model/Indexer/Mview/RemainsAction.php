<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Indexer\Mview;

use Magento\Framework\Indexer\IndexerInterfaceFactory;
use Magento\Framework\Mview\ActionInterface;
use Tsum\CashFlow\Model\Indexer\RemainsAggregation;

class RemainsAction implements ActionInterface
{
    /**
     * @var IndexerInterfaceFactory
     */
    private $indexerFactory;

    /**
     * @param IndexerInterfaceFactory $indexerFactory
     */
    public function __construct(IndexerInterfaceFactory $indexerFactory)
    {
        $this->indexerFactory = $indexerFactory;
    }

    /**
     * @param int[] $ids
     * @return void
     * @api
     */
    public function execute($ids)
    {
        $indexer = $this->indexerFactory->create()->load(RemainsAggregation::INDEXER_ID);
        $indexer->reindexList($ids);
    }
}
