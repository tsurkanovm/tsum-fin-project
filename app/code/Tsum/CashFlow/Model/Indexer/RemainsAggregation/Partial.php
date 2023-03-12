<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Indexer\RemainsAggregation;

use Psr\Log\LoggerInterface;

class Partial
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

    public function executeList(array $periods): Partial
    {
        $this->logger->debug("Partial Agg Index works! " . print_r($periods));

        return $this;
    }
}
