<?php

declare(strict_types=1);

namespace Tsum\CashFlowImport\Controller\Adminhtml\Staging;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;

class Upload implements HttpPostActionInterface
{

    public function __construct(
        private readonly JsonFactory $resultJsonFactory,
        private readonly UploaderFactory $fileUploaderFactory,
        private readonly ManagerInterface $messageManager
    ) {
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $result   = $this->upload();
        $response = $this->resultJsonFactory->create();
        $response->setData($result);

        return $response;
    }

    /**
     * Upload file
     *
     * @return array
     */
    private function upload(): array
    {
        $result    = [];
        $uploader  = $this->fileUploaderFactory->create(['fileId' => 'upload']);
        $uploader->setAllowedExtensions(['xls']);
        $uploader->setAllowRenameFiles(true);

        try {
            // @todo create if missed the import folder

            $result = $uploader->save(DirectoryList::VAR_DIR . DIRECTORY_SEPARATOR . 'import');

            // @todo run import service, if it is done successfully - redirect on staging grid
            // delete imported file anyway
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $result;
    }
}
