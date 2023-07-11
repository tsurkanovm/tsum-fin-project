<?php
namespace Tsum\CashFlow\Controller\Adminhtml\Incomes;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Tsum\CashFlow\Api\Data\IncomesInterface;
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

    public function __construct(
        Action\Context $context,
        private readonly DataPersistorInterface $dataPersistor,
        private readonly Incomes $incomesResource,
        private readonly IncomesFactory $incomesFactory
    ) {
        parent::__construct($context);
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute(): Redirect|ResultInterface
    {
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (empty($data[IncomesInterface::ENTITY_ID])) {
                $data[IncomesInterface::ENTITY_ID] = null;
            }
            if (isset($data[IncomesInterface::IS_ACTIVE]) && $data[IncomesInterface::IS_ACTIVE] === 'true') {
                $data[IncomesInterface::IS_ACTIVE] = 1;
            }
            /** @var IncomesModel $model */
            $model = $this->incomesFactory->create();
            $id = $this->getRequest()->getParam(IncomesInterface::ENTITY_ID);
            if ($id) {
                try {
                    $this->incomesResource->load($model, $id);
                } catch (\Exception) {
                    $this->messageManager->addErrorMessage(__('This income no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $this->incomesResource->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the Income.'));
                $this->dataPersistor->clear('tsum_incomes');

                return $this->processResultRedirect($model, $resultRedirect, $data);
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Income.'));
            }

            $this->dataPersistor->set('tsum_incomes', $data);

            return $resultRedirect->setPath(
                '*/*/edit',
                [IncomesInterface::ENTITY_ID => $this->getRequest()->getParam(IncomesInterface::ENTITY_ID)]
            );
        }

        return $resultRedirect->setPath('*/*/');
    }

    private function processResultRedirect(IncomesModel $model, Redirect $resultRedirect, array $data): Redirect
    {
        $redirect = $data['back'] ?? 'close';
        $editPath = $this->getEditControllerPath();
        if ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        } elseif ($redirect === 'duplicate') {
            $this->messageManager->addSuccessMessage(__('You duplicated the income.'));
            $this->dataPersistor->set('tsum_incomes', $data);
            $resultRedirect->setPath($editPath);
        }

        return $resultRedirect;
    }

    private function getEditControllerPath(): string
    {
        return '*/*/edit' . ($this->dataPersistor->get(Edit::MOVE_KEY) === EditIn::MOVE_VALUE ? 'in' : '');
    }
}
