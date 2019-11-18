<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tsum\CashFlow\Controller\Adminhtml\CfItem;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use Tsum\CashFlow\Model\ResourceModel\CfItem;
use Tsum\CashFlow\Model\CfItem as CfItemModel;
use Tsum\CashFlow\Model\CfItemFactory;

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
    const ADMIN_RESOURCE = 'Tsum_CashFlow::cfitem';

    /**
     * @var CfItem
     */
    protected $cfItem;

    /**
     * @var CfItemFactory
     */
    protected $cfItemFactory;


    public function __construct(
        Context $context,
        CfItem $cfItem,
        CfItemFactory $cfItemFactory
    ) {
        parent::__construct($context);
        $this->cfItem = $cfItem;
        $this->cfItemFactory = $cfItemFactory;
    }

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam(CfItemModel::ENTITY_ID);
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if ($id) {
            $title = "";
            try {
                // init model and delete
                $model = $this->cfItemFactory->create();
                $this->cfItem->load($model, $id);
                
                $title = $model->getTitle();
                $this->cfItem->delete($model);
                
                // display success message
                $this->messageManager->addSuccessMessage(__('The item has been deleted.'));
                
                // go to grid
                $this->_eventManager->dispatch('adminhtml_tsum_cfitem_on_delete', [
                    'title' => $title,
                    'status' => 'success'
                ]);
                
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_tsum_cfitem_on_delete',
                    ['title' => $title, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', [CfItemModel::ENTITY_ID => $id]);
            }
        }
        
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find an item to delete.'));
        
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
