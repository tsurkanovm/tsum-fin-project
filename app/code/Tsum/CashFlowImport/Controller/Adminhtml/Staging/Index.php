<?php

declare(strict_types=1);

namespace Tsum\CashFlowImport\Controller\Adminhtml\Staging;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Tsum\CashFlowImport\Api\StagingRepositoryInterface;

class Index implements HttpGetActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly StagingRepositoryInterface $stagingRepository,
        private readonly ManagerInterface $messageManager,
        private readonly RedirectFactory $resultRedirectFactory,
    ) {
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->getConfig()->getTitle()->prepend(__('Cash Flow'));
        $resultPage->getConfig()->getTitle()->prepend(__('Staging Import Form'));

        if ($this->stagingRepository->isNotEmpty()) {
            $this->messageManager->addErrorMessage('Staging already has records! Please check.');

            return $this->resultRedirectFactory->create()->setPath('cf_import/staging/grid');
        }

        return $resultPage;
    }
}
