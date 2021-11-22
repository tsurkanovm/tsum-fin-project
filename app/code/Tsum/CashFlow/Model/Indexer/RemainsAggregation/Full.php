<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Indexer\RemainsAggregation;

use Psr\Log\LoggerInterface;

class Full
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute(): Full
    {
        $this->logger->debug("Agg Index works!");
        return $this;
    }
}
