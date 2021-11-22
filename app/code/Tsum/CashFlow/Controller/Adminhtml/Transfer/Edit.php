<?php
namespace Tsum\CashFlow\Controller\Adminhtml\Transfer;

use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\View\Result\PageFactory;
use Tsum\CashFlow\Model\ResourceModel\Transfer as TransferResource;
use Tsum\CashFlow\Model\Transfer;
use Tsum\CashFlow\Model\TransferFactory;

class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Tsum_CashFlow::transfer';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var TransferResource
     */
    private $transferResource;

    /**
     * @var TransferFactory
     */
    private $transferFactory;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        TransferResource $transferResource,
        TransferFactory $transferFactory,
        DataPersistorInterface $dataPersistor
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->transferResource = $transferResource;
        $this->transferFactory = $transferFactory;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     *
     * @return Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Tsum_CashFlow::transfer')
            ->addBreadcrumb(__('Out'), __('Out'))
            ->addBreadcrumb(__('Manage transfer'), __('Manage transfer'));
        return $resultPage;
    }

    /**
     * @return Page|Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam(Transfer::ENTITY_ID);
        $model = $this->transferFactory->create();

        if ($id) {
            $this->transferResource->load($model, $id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This transfer no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit transfer') : __('New transfer'),
            $id ? __('Edit transfer') : __('New transfer')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('transfer'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New transfer'));

        return $resultPage;
    }
}
