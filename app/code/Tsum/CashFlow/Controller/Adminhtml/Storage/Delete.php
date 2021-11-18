<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tsum\CashFlow\Controller\Adminhtml\Storage;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use Tsum\CashFlow\Model\ResourceModel\Storage;
use Tsum\CashFlow\Model\StorageFactory;

/**
 * Delete CashFlow Storage action.
 */
class Delete extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Tsum_CashFlow::storage';

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * @var StorageFactory
     */
    protected $storageFactory;

    /**
     * Delete constructor.
     * @param Context $context
     * @param Storage $storage
     * @param StorageFactory $storageFactory
     */
    public function __construct(
        Context $context,
        Storage $storage,
        StorageFactory $storageFactory
    ) {
        parent::__construct($context);
        $this->storage = $storage;
        $this->storageFactory = $storageFactory;
    }

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('storage_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if ($id) {
            $title = "";
            try {
                // init model and delete
                $model = $this->storageFactory->create();
                $this->storage->load($model, $id);
                
                $title = $model->getTitle();
                $this->storage->delete($model);
                
                // display success message
                $this->messageManager->addSuccessMessage(__('The storage has been deleted.'));
                
                // go to grid
                $this->_eventManager->dispatch('adminhtml_tsum_storage_on_delete', [
                    'title' => $title,
                    'status' => 'success'
                ]);
                
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_tsum_storage_on_delete',
                    ['title' => $title, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['storage_id' => $id]);
            }
        }
        
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a storage to delete.'));
        
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
