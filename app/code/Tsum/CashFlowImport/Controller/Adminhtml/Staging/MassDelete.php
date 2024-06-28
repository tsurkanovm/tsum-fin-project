<?php

namespace Tsum\CashFlowImport\Controller\Adminhtml\Staging;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Ui\Component\MassAction\Filter;
use Tsum\CashFlowImport\Model\ResourceModel\Staging\CollectionFactory;

class MassDelete implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Tsum_CashFlow::incomes';

    public function __construct(
        private readonly Filter $filter,
        private readonly CollectionFactory $collectionFactory,
        private readonly RedirectFactory $resultRedirectFactory,
        private readonly ManagerInterface $messageManager,
    ) {
    }

    /**
     * @throws LocalizedException|\Exception
     */
    public function execute(): Redirect
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $item) {
            $item->delete();
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));

        return $this->resultRedirectFactory->create()->setPath('cf_import/staging/grid');
    }
}
