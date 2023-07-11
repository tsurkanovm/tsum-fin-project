<?php
namespace Tsum\CashFlow\Controller\Adminhtml\Incomes;

use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\View\Result\PageFactory;
use Tsum\CashFlow\Api\Data\IncomesInterface;
use Tsum\CashFlow\Model\ResourceModel\Incomes as IncomesResource;
use Tsum\CashFlow\Model\IncomesFactory;

class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Tsum_CashFlow::incomes';

    const MOVE_KEY = 'tsum_incomes_in';
    const MOVE_VALUE = 0;
    const TYPE_OF_MOVE = 'Out';


    public function __construct(
        Action\Context $context,
        private readonly PageFactory $resultPageFactory,
        private readonly IncomesResource $incomesResource,
        private readonly IncomesFactory $incomesFactory,
        private readonly DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
    }

    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Tsum_CashFlow::incomes')
            ->addBreadcrumb(__(static::TYPE_OF_MOVE), __(static::TYPE_OF_MOVE))
            ->addBreadcrumb(__('Manage Incomes'), __('Manage Incomes'));
        return $resultPage;
    }

    /**
     * @return Page|Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $this->dataPersistor->set(self::MOVE_KEY, static::MOVE_VALUE);
        $model = $this->incomesFactory->create();

        if ($id = $this->getRequest()->getParam(IncomesInterface::ENTITY_ID)) {
            $this->incomesResource->load($model, $id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This ' . static::TYPE_OF_MOVE . ' income no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $type = static::TYPE_OF_MOVE;
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __("Edit Income ({$type})") : __("New {$type}"),
            $id ? __("Edit Income ({$type})") : __("New {$type}")
        );
        $resultPage->getConfig()->getTitle()->prepend(__($type));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __("New {$type}"));

        return $resultPage;
    }
}
