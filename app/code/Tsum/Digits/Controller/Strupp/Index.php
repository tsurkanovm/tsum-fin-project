<?php
declare(strict_types=1);

namespace Tsum\Digits\Controller\Strupp;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Controller for the 'digits/strupp/index' URL route.
 */
class Index implements HttpGetActionInterface
{
    public function __construct(
        private readonly PageFactory $resultPageFactory
    ) {
    }

    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}
