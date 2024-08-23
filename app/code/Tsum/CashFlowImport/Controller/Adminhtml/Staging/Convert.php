<?php

namespace Tsum\CashFlowImport\Controller\Adminhtml\Staging;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Tsum\CashFlowImport\Model\ConvertAction;

class Convert implements HttpGetActionInterface
{
    public function __construct(
        private readonly RedirectFactory $resultRedirectFactory,
        private readonly ConvertAction $convertAction,
        private readonly ManagerInterface $messageManager,
    ) {
    }
    public function execute(): Redirect
    {
        // @todo get only checked items in grid, like mass action
        // or another button for convert transfers separately
        try {
            if ($convertedDocsAmount = $this->convertAction->convert()) {
                $this->messageManager->addSuccessMessage("Successfully converted $convertedDocsAmount documents");
            }
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }
        // @todo redirect to income? or add links in message
        return $this->resultRedirectFactory->create()->setPath('cf_import/staging/grid');
    }
}
