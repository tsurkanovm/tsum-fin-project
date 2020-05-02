<?php
namespace Tsum\CashFlow\Controller\Adminhtml\Incomes;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use Tsum\CashFlow\Model\Incomes;
use Tsum\CashFlow\Model\IncomesFactory;
use Tsum\CashFlow\Model\ResourceModel\Incomes as IncomesResource;

class Delete extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Tsum_CashFlow::incomes';

    /**
     * @var IncomesResource
     */
    private $incomesResource;

    /**
     * @var IncomesFactory
     */
    private $incomesFactory;

    public function __construct(
        Context $context,
        IncomesResource $incomesResource,
        IncomesFactory $incomesFactory
    ) {
        parent::__construct($context);
        $this->incomesResource = $incomesResource;
        $this->incomesFactory = $incomesFactory;
    }

    /**
     * @return Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam(Incomes::ENTITY_ID);
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if ($id) {
            try {
                // init model and delete
                $model = $this->incomesFactory->create();
                $this->incomesResource->load($model, $id);

                $this->incomesResource->delete($model);
                
                // display success message
                $this->messageManager->addSuccessMessage(__('The income has been deleted.'));
                
                // go to grid
                $this->_eventManager->dispatch('adminhtml_tsum_incomes_on_delete', [
                    'id' => $id,
                    'status' => 'success'
                ]);
                
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_tsum_incomes_on_delete',
                    ['id' => $id, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', [Incomes::ENTITY_ID => $id]);
            }
        }
        
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find an income to delete.'));
        
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
