<?php

declare(strict_types=1);

namespace Tsum\CashFlowImport\Controller\Adminhtml\Staging;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Filesystem\Io\File;

class Upload implements HttpPostActionInterface
{
    public const PRYVAT_IMPORT_FOLDER_NAME = 'pryvat_import';

    public function __construct(
        private readonly JsonFactory $resultJsonFactory,
        private readonly UploaderFactory $fileUploaderFactory,
        private readonly ManagerInterface $messageManager,
        private readonly DirectoryList $directoryList,
        private readonly File $file,
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
            $importFolderPath = $this->directoryList->getPath(DirectoryList::VAR_DIR)
                . DIRECTORY_SEPARATOR . self::PRYVAT_IMPORT_FOLDER_NAME;

            $this->file->checkAndCreateFolder($importFolderPath);

            $result = $uploader->save(
                DirectoryList::VAR_DIR . DIRECTORY_SEPARATOR . self::PRYVAT_IMPORT_FOLDER_NAME
            );
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $result;
    }
}
