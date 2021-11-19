<?php
declare(strict_types=1);
namespace Tsum\Knowledge\Controller\Add;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Element\UiComponentFactory;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var UiComponentFactory
     */
    private $uiFactory;

    public function __construct(
        Context $context,
        UiComponentFactory $uiFactory,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->uiFactory = $uiFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        if ($this->getRequest()->isAjax()) {
            $component = $this->uiFactory->create($this->_request->getParam('namespace'));
            $this->prepareComponent($component);
            $this->_response->appendBody((string) $component->render());
        } else {
            return $this->resultPageFactory->create();
        }
    }

    private function prepareComponent(UiComponentInterface $component) : void
    {
        foreach ($component->getChildComponents() as $child) {
            $this->prepareComponent($child);
        }
        $component->prepare();
    }
}
