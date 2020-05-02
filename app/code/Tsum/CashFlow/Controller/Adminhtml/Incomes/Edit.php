<?php
namespace Tsum\CashFlow\Controller\Adminhtml\Incomes;

use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Tsum\CashFlow\Model\ResourceModel\Incomes as IncomesResource;
use Tsum\CashFlow\Model\Incomes;
use Tsum\CashFlow\Model\IncomesFactory;

/**
 * Edit CMS page action.
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Tsum_CashFlow::incomes';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var IncomesResource
     */
    private $incomesResource;

    /**
     * @var IncomesFactory
     */
    private $incomesFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        IncomesResource $incomesResource,
        IncomesFactory $incomesFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->incomesResource = $incomesResource;
        $this->incomesFactory = $incomesFactory;
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
        $resultPage->setActiveMenu('Tsum_CashFlow::incomes')
            ->addBreadcrumb(__('Incomes'), __('Incomes'))
            ->addBreadcrumb(__('Manage Incomes'), __('Manage Incomes'));
        return $resultPage;
    }

    /**
     * @return Page|Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam(Incomes::ENTITY_ID);
        $model = $this->incomesFactory->create();

        if ($id) {
            $this->incomesResource->load($model, $id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This income no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Income') : __('New Income'),
            $id ? __('Edit Income') : __('New Income')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Incomes'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Income'));

        return $resultPage;
    }
}
