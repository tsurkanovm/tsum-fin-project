<?php

namespace Tsum\CashFlowImport\Controller\Adminhtml\Staging;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;

class Grid extends Action
{
    public const ADMIN_RESOURCE = 'Tsum_CashFlow::incomes';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return Page
     */
    public function execute()
    {
        $page = $this->resultPageFactory->create();

        $page->setActiveMenu('Tsum_CashFlow::incomes');
        $page->getConfig()->getTitle()->prepend(__('Staging Cash Flow'));

        return $page;
    }
}
