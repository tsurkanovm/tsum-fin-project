<?php

declare(strict_types=1);

namespace Tsum\CashFlowImport\Controller\Adminhtml\Staging;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Tsum\CashFlowImport\Model\Pryvat\ImportAction;
use Magento\Framework\Filesystem\Io\File;

class Save implements HttpPostActionInterface
{
    public function __construct(
        private readonly JsonFactory $resultJsonFactory,
        private readonly ImportAction $importAction,
        private readonly RequestInterface $request,
        private readonly DirectoryList $directoryList,
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

        $this->importAction->execute($this->request, $importFilePath);
        // @todo if it is done successfully - redirect on staging grid
        // @todo delete imported file ($importFilePath) if everything OK
        $response = $this->resultJsonFactory->create();
        $response->setData([]);

        return $response;
    }
}
