<?php
namespace Tsum\CashFlow\Controller\Adminhtml\Import;

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;

class FromFile implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Tsum_CashFlow::incomes';

    public function __construct(
        private readonly ResultFactory $resultFactory
    ) {
    }

    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

 //       $resultPage->setActiveMenu('Magento_TaxImportExport::system_convert_tax');
//        $resultPage->addContent(
//            $resultPage->getLayout()->createBlock(
//                \Magento\TaxImportExport\Block\Adminhtml\Rate\ImportExportHeader::class
//            )
//        );
        $resultPage->addContent(
            /** @phpstan-ignore-next-line */
            $resultPage->getLayout()->createBlock(\Tsum\CashFlow\Block\Adminhtml\Import\FromFile::class)
        );

        $resultPage->getConfig()->getTitle()->prepend(__('Import Incomes and Transfers from a file'));

        return $resultPage;
    }
}
