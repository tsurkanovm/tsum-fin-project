<?php

declare(strict_types=1);

namespace Tsum\CashFlowImport\Controller\Adminhtml\Staging;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Tsum_CashFlow::incomes'; //@todo - replace

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        // @todo checkup staging - if it is not empty - error msg and return

        $resultPage->getConfig()->getTitle()->prepend(__('CMS'));
        $resultPage->getConfig()->getTitle()->prepend(__('Staging Import Form'));

        return $resultPage;
    }
}
