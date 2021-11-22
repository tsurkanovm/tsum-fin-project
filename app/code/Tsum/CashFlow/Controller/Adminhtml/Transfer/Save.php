<?php
namespace Tsum\CashFlow\Controller\Adminhtml\Transfer;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Tsum\CashFlow\Model\ResourceModel\Transfer;
use Tsum\CashFlow\Model\Transfer as TransferModel;
use Tsum\CashFlow\Model\TransferFactory;

class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Tsum_CashFlow::incomes';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var Transfer
     */
    protected $transferResource;

    /**
     * @var TransferFactory
     */
    protected $transferFactory;

    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        Transfer $transferResource,
        TransferFactory $transferFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->transferFactory = $transferFactory;
        $this->transferResource = $transferResource;

        parent::__construct($context);
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (empty($data[TransferModel::ENTITY_ID])) {
                $data[TransferModel::ENTITY_ID] = null;
            }

            /** @var TransferModel $model */
            $model = $this->transferFactory->create();
            $id = $this->getRequest()->getParam(TransferModel::ENTITY_ID);
            if ($id) {
                try {
                    $this->transferResource->load($model, $id);
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage(__('This transfer no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $this->transferResource->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the transfer.'));

                return $this->processResultRedirect($model, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the transfer.'));
            }

            $this->dataPersistor->set('tsum_transfer', $data);

            return $resultRedirect->setPath(
                '*/*/edit',
                [TransferModel::ENTITY_ID => $this->getRequest()->getParam(TransferModel::ENTITY_ID)]
            );
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process result redirect
     *
     * @param transferModel $model
     * @param Redirect $resultRedirect
     *
     * @return Redirect
     */
    private function processResultRedirect(TransferModel $model, Redirect $resultRedirect)
    {
        $this->dataPersistor->clear('tsum_transfer');
        if ($this->getRequest()->getParam('back')) {
            return $resultRedirect->setPath(
                '*/*/edit',
                [TransferModel::ENTITY_ID => $model->getId(), '_current' => true]
            );
        }

        return $resultRedirect->setPath('*/*/');
    }
}
