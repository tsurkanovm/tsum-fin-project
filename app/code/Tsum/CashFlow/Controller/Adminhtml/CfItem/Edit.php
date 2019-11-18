<?php
namespace Tsum\CashFlow\Controller\Adminhtml\CfItem;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\App\Action;
use Tsum\CashFlow\Model\ResourceModel\CfItem;
use Tsum\CashFlow\Model\CfItem as CfItemModel;
use Tsum\CashFlow\Model\CfItemFactory;

/**
 * Edit CMS page action.
 */
class Edit extends \Magento\Backend\App\Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Tsum_CashFlow::cfitem';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var CfItem
     */
    protected $cfItem;

    /**
     * @var CfItemFactory
     */
    protected $cfItemFactory;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param CfItem $cfItem
     * @param CfItemFactory $cfItemFactory
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        CfItem $cfItem,
        CfItemFactory $cfItemFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->cfItem = $cfItem;
        $this->cfItemFactory = $cfItemFactory;
        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Tsum_CashFlow::cfitem')
            ->addBreadcrumb(__('CF item'), __('CF item'))
            ->addBreadcrumb(__('Manage Items'), __('Manage Items'));
        return $resultPage;
    }

    /**
     * Edit CMS page
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam(CfItemModel::ENTITY_ID);
        $model = $this->cfItemFactory->create();

        // 2. Initial checking
        if ($id) {
            $this->cfItem->load($model, $id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This item no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('tsum_cfitem', $model);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Cash Flow Item') : __('New Cash Flow Item'),
            $id ? __('Edit Cash Flow Item') : __('New Cash Flow Item')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Items'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Cash Flow Item'));

        return $resultPage;
    }
}
