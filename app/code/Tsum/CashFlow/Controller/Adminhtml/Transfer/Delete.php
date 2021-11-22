<?php
namespace Tsum\CashFlow\Controller\Adminhtml\Transfer;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use Tsum\CashFlow\Model\Transfer;
use Tsum\CashFlow\Model\TransferFactory;
use Tsum\CashFlow\Model\ResourceModel\Transfer as TransferResource;

class Delete extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Tsum_CashFlow::incomes';

    /**
     * @var TransferResource
     */
    private $transferResource;

    /**
     * @var TransferFactory
     */
    private $transferFactory;

    public function __construct(
        Context $context,
        TransferResource $transferResource,
        TransferFactory $transferFactory
    ) {
        parent::__construct($context);
        $this->transferResource = $transferResource;
        $this->transferFactory = $transferFactory;
    }

    /**
     * @return Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam(Transfer::ENTITY_ID);
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                // init model and delete
                $model = $this->transferFactory->create();
                $this->transferResource->load($model, $id);

                $this->transferResource->delete($model);

                // display success message
                $this->messageManager->addSuccessMessage(__('The income has been deleted.'));

                // go to grid
                $this->_eventManager->dispatch('adminhtml_tsum_transfer_on_delete', [
                    'id' => $id,
                    'status' => 'success'
                ]);

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_tsum_transfer_on_delete',
                    ['id' => $id, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', [Transfer::ENTITY_ID => $id]);
            }
        }

        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find an income to delete.'));

        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
