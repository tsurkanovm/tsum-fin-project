<?php
namespace Tsum\CashFlow\Controller\Adminhtml\Storage;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Tsum\CashFlow\Api\Data\StorageInterface;
use Tsum\CashFlow\Model\ResourceModel\Storage;
use Tsum\CashFlow\Model\StorageFactory;

/**
 * Save CashFlow storage action.
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Tsum_CashFlow::storage';


    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * @var StorageFactory
     */
    protected $storageFactory;


    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        Storage $storage ,
        StorageFactory $storageFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->storageFactory = $storageFactory;
        $this->storage = $storage;

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
            if (empty($data['storage_id'])) {
                $data['storage_id'] = null;
            }

            /** @var StorageInterface $model */
            $model = $this->storageFactory->create();

            $id = $this->getRequest()->getParam('storage_id');
            if ($id) {
                try {
                    $this->storage->load($model, $id);
                    if (!$model->getId()) {
                        throw new LocalizedException('This storage no longer exists.');
                    }
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This storage no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

//            $this->_eventManager->dispatch(
//                'tsum_storage_prepare_save',
//                ['storage' => $model, 'request' => $this->getRequest()]
//            );

            try {
                $this->storage->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the storage.'));

                return $this->processResultRedirect($model, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the storage.'));
            }

            $this->dataPersistor->set('tsum_cash_storage', $data);

            return $resultRedirect->setPath('*/*/edit', ['storage_id' => $this->getRequest()->getParam('storage_id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process result redirect
     *
     * @param StorageInterface $model
     * @param \Magento\Backend\Model\View\Result\Redirect $resultRedirect
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws LocalizedException
     */
    private function processResultRedirect($model, $resultRedirect)
    {
        $this->dataPersistor->clear('tsum_cash_storage');
        if ($this->getRequest()->getParam('back')) {
            return $resultRedirect->setPath('*/*/edit', ['storage_id' => $model->getId(), '_current' => true]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
