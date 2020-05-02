<?php
namespace Tsum\CashFlow\Controller\Adminhtml\CfItem;

use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Tsum\CashFlow\Model\ResourceModel\CfItem;
use Tsum\CashFlow\Model\CfItem as CfItemModel;
use Tsum\CashFlow\Model\CfItemFactory;

class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Tsum_CashFlow::cfitem';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var CfItem
     */
    private $cfItem;

    /**
     * @var CfItemFactory
     */
    private $cfItemFactory;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param CfItem $cfItem
     * @param CfItemFactory $cfItemFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        CfItem $cfItem,
        CfItemFactory $cfItemFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->cfItem = $cfItem;
        $this->cfItemFactory = $cfItemFactory;
        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Tsum_CashFlow::cfitem')
            ->addBreadcrumb(__('CF item'), __('CF item'))
            ->addBreadcrumb(__('Manage Items'), __('Manage Items'));
        return $resultPage;
    }

    /**
     * @return Page|Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam(CfItemModel::ENTITY_ID);
        $model = $this->cfItemFactory->create();

        if ($id) {
            $this->cfItem->load($model, $id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This item no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

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
