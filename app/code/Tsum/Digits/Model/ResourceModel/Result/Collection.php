<?php

namespace Tsum\Digits\Model\ResourceModel\Result;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Tsum\Digits\Model\Result', 'Tsum\Digits\Model\ResourceModel\Result');
    }
}
