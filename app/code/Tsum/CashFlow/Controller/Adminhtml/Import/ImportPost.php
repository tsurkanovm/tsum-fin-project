<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Controller\Adminhtml\Import;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Tsum\CashFlow\Api\ImportFromFileInterface;

class ImportPost implements HttpPostActionInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly ManagerInterface $messageManager,
        private readonly RedirectInterface $redirect,
        private readonly ResultFactory $resultFactory,
        private readonly ImportFromFileInterface $importHandler,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $importRatesFile = $this->request->getFiles('import_documents_file');
        if ($this->request->isPost() && isset($importRatesFile['tmp_name'])) {
            try {
                $this->importHandler->import($importRatesFile);

                $this->messageManager->addSuccess(__('Documents has been imported.')->getText());
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(__('Invalid file upload attempt')->getText());
            }
        } else {
            $this->messageManager->addError(__('Invalid file upload attempt')->getText());
        }
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->redirect->getRedirectUrl());

        return $resultRedirect;
    }
}
