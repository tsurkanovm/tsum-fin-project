<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Tsum\CashFlow\Api\Data\RegistrationDocumentInterface;
use Tsum\CashFlow\Model\Aggregation\Status\Processor;

/**
 * Dispatcher for the `tsum_cf_transfer_delete_after` event.
 */
class TransferDeleteAfterObserver implements ObserverInterface
{
    public function __construct(
        private readonly Processor $statusProcessor
    ) {
    }
    /**
     * Handle the `tsum_cf_transfer_delete_after` event.
     *
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer): void
    {
        /** @var RegistrationDocumentInterface $registrationDocument */
        $registrationDocument = $observer->getData('object');
        $this->statusProcessor->process($registrationDocument, true);
    }
}
