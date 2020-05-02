<?php
namespace Tsum\CashFlow\Controller\Adminhtml\Incomes;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Tsum\CashFlow\Model\ResourceModel\Incomes;
use Tsum\CashFlow\Model\Incomes as IncomesModel;
use Tsum\CashFlow\Model\IncomesFactory;

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
     * @var Incomes
     */
    protected $incomesResource;

    /**
     * @var IncomesFactory
     */
    protected $incomesFactory;

    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        Incomes $incomesResource,
        IncomesFactory $incomesFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->incomesFactory = $incomesFactory;
        $this->incomesResource = $incomesResource;

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
            if (empty($data[IncomesModel::ENTITY_ID])) {
                $data[IncomesModel::ENTITY_ID] = null;
            }

            /** @var IncomesModel $model */
            $model = $this->incomesFactory->create();
            $id = $this->getRequest()->getParam(IncomesModel::ENTITY_ID);
            if ($id) {
                try {
                    $this->incomesResource->load($model, $id);
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage(__('This income no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $this->incomesResource->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the Income.'));

                return $this->processResultRedirect($model, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Income.'));
            }

            $this->dataPersistor->set('tsum_incomes', $data);

            return $resultRedirect->setPath(
                '*/*/edit',
                [IncomesModel::ENTITY_ID => $this->getRequest()->getParam(IncomesModel::ENTITY_ID)]
            );
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process result redirect
     *
     * @param IncomesModel $model
     * @param Redirect $resultRedirect
     *
     * @return Redirect
     */
    private function processResultRedirect(IncomesModel $model, Redirect $resultRedirect)
    {
        $this->dataPersistor->clear('tsum_incomes');
        if ($this->getRequest()->getParam('back')) {
            return $resultRedirect->setPath(
                '*/*/edit',
                [IncomesModel::ENTITY_ID => $model->getId(), '_current' => true]
            );
        }

        return $resultRedirect->setPath('*/*/');
    }
}
