<?php

declare(strict_types=1);

namespace Tsum\CashFlowImport\Controller\Adminhtml\Staging;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Message\ManagerInterface;
use Tsum\CashFlowImport\Model\Pryvat\ImportAction;

class Save implements HttpPostActionInterface
{
    public function __construct(
        private readonly RedirectFactory $resultRedirectFactory,
        private readonly ImportAction $importAction,
        private readonly RequestInterface $request,
        private readonly DirectoryList $directoryList,
        private readonly ManagerInterface $messageManager,
    ) {
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        // @todo validate request
        //$this->validate();
        $upload = $this->request->getParam('upload')[0];

        $importFilePath = $this->directoryList->getRoot()
            . DIRECTORY_SEPARATOR . $upload['path'] . DIRECTORY_SEPARATOR . $upload['file'];

        try {
            $this->importAction->execute($this->request, $importFilePath);
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->resultRedirectFactory->create()->setPath('cf_import/staging/grid');
    }
}
