<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tsum\CashFlow\Controller\Adminhtml\Storage;

use Magento\Backend\App\Action\Context;
use Tsum\CashFlow\Api\Data\StorageInterface;
use Tsum\CashFlow\Api\StorageRepositoryInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Tsum\CashFlow\Model\Storage;

/**
 * CashFlow storage grid inline edit controller
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Tsum_CashFlow::storage';

    /**
     * @var StorageRepositoryInterface
     */
    protected $storageRepository;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param StorageRepositoryInterface $storageRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        StorageRepositoryInterface $storageRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->storageRepository = $storageRepository;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $storageItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($storageItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($storageItems) as $storageId) {
            /** @var Storage $storage */
            $storage = $this->storageRepository->getById($storageId);
            try {
                $storage->setData($storageItems[$storageId]);
                $this->storageRepository->save($storage);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithPageId($storage, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithPageId($storage, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithPageId(
                    $storage,
                    __('Something went wrong while saving the storage.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add storage title to error message
     *
     * @param StorageInterface $storage
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithPageId(StorageInterface $storage, $errorText)
    {
        return '[Storage ID: ' . $storage->getId() . '] ' . $errorText;
    }
}
