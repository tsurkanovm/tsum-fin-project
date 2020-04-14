<?php

namespace Tsum\Knowledge\ViewModel;

class AddFormViewModel implements \Magento\Framework\View\Element\Block\ArgumentInterface
{

    public function isEnabled(): bool
    {
        return true;
    }
}
