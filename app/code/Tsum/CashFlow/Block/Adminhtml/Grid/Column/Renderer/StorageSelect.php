<?php

namespace Tsum\CashFlow\Block\Adminhtml\Grid\Column\Renderer;


class StorageSelect extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Select
{
    protected function _getOptions()
    {
        return [0 => 'Test', 1 => 'Test33'];
    }
}
