<?php
namespace Tsum\CashFlow\Controller\Adminhtml\Incomes;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Cms\Model\Block;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Tsum\CashFlow\Api\Data\CfItemInterface;
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

    /**
     * Process result redirect
     *
     * @param IncomesModel $model
     * @param Redirect $resultRedirect
     *
     * @return Redirect
     * @throws AlreadyExistsException
     */
    private function processResultRedirect(IncomesModel $model, Redirect $resultRedirect, array $data): Redirect
    {
        $redirect = $data['back'] ?? 'close';
        $editPath = '*/*/edit' . ($this->dataPersistor->get('tsum_incomes_in') == 1 ? 'in' : '');
        if ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        } elseif ($redirect === 'duplicate') {
            $this->messageManager->addSuccessMessage(__('You duplicated the income.'));
            $this->dataPersistor->set('tsum_incomes', $data);
            $resultRedirect->setPath($editPath);
        }

        return $resultRedirect;
    }
}
