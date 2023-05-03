<?php

namespace Tsum\CashFlow\Controller\Adminhtml\Transfer;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;

class Index extends Action
{
    const ADMIN_RESOURCE = 'Tsum_CashFlow::incomes';

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
        return parent::__construct($context);
    }

    /**
     * @return Page
     */
    public function execute()
    {
        $page = $this->resultPageFactory->create();

        $page->setActiveMenu('Tsum_CashFlow::transfer');
        $page->getConfig()->getTitle()->prepend(__('Transfers'));

        return $page;
    }
}
