<?php
namespace Tsum\CashFlow\Controller\Adminhtml\CfItem;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Tsum\CashFlow\Api\Data\StorageInterface;
use Tsum\CashFlow\Model\ResourceModel\CfItem;
use Tsum\CashFlow\Model\CfItem as CfItemModel;
use Tsum\CashFlow\Model\CfItemFactory;

/**
 * Save CashFlow item action.
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Tsum_CashFlow::cfitem';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var CfItem
     */
    protected $cfItem;

    /**
     * @var CfItemFactory
     */
    protected $cfItemFactory;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param CfItem $cfItem
     * @param CfItemFactory $cfItemFactory
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        CfItem $cfItem,
        CfItemFactory $cfItemFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->cfItemFactory = $cfItemFactory;
        $this->cfItem = $cfItem;

        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (empty($data[CfItemModel::ENTITY_ID])) {
                $data[CfItemModel::ENTITY_ID] = null;
            }

            /** @var CfItemModel $model */
            $model = $this->cfItemFactory->create();

            $id = $this->getRequest()->getParam(CfItemModel::ENTITY_ID);
            if ($id) {
                try {
                    $this->cfItem->load($model, $id);
                    if (!$model->getId()) {
                        throw new LocalizedException(__('This item no longer exists.'));
                    }
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This item no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $this->cfItem->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the Cash Flow Item.'));

                return $this->processResultRedirect($model, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Cash Flow Item.'));
            }

            $this->dataPersistor->set('tsum_cf_item', $data);

            return $resultRedirect->setPath('*/*/edit',
                [CfItemModel::ENTITY_ID => $this->getRequest()->getParam(CfItemModel::ENTITY_ID)]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process result redirect
     *
     * @param CfItemModel $model
     * @param Redirect $resultRedirect
     * @return Redirect
     * @throws LocalizedException
     */
    private function processResultRedirect(CfItemModel $model, Redirect $resultRedirect)
    {
        $this->dataPersistor->clear('tsum_cash_storage');
        if ($this->getRequest()->getParam('back')) {
            return $resultRedirect->setPath('*/*/edit', ['storage_id' => $model->getId(), '_current' => true]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
