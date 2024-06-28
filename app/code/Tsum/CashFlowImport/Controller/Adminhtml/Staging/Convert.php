<?php

namespace Tsum\CashFlowImport\Controller\Adminhtml\Staging;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Tsum\CashFlowImport\Model\ConvertAction;

class Convert implements HttpPostActionInterface
{
    public function __construct(
        private readonly RedirectFactory $resultRedirectFactory,
        private readonly ConvertAction $convertAction,
        private readonly ManagerInterface $messageManager,
    ) {
    }
    public function execute(): Redirect
    {
        try {
            $this->convertAction->convert();
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $this->resultRedirectFactory->create();
    }
}
