<?php

namespace Tsum\CashFlow\Block\Adminhtml\Grid\Column\Renderer;

use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\Options\Converter;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\Select;
use Tsum\CashFlow\Model\Source\CfItem;
use Tsum\CashFlow\Model\Source\CfItemFactory;

class StorageSelect extends Select
{
    private $_cfItemSourceFactory;

    public function __construct(
        Context $context,
        Converter $converter,
        CfItemFactory $cfItemSourceFactory
    ) {
        $this->_cfItemSourceFactory = $cfItemSourceFactory;
        parent::__construct($context, $converter);
    }

    protected function _getOptions()
    {
        $res = [];
        /** @var $cfItemSourceOut CfItem */
        $cfItemSourceOut = $this->_cfItemSourceFactory->create(['cfType' => 'OUT']);
        foreach ($cfItemSourceOut->getCfItems() as $item) {
            $res[$item['cf_item_id']] = $item['title'];
        }

        return $res;
    }
}
