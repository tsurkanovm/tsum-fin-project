<?php

namespace Tsum\CashFlow\Model\ResourceModel\Remains;

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
        $this->_init('Tsum\CashFlow\Model\Remains', 'Tsum\CashFlow\Model\ResourceModel\Remains');
    }
}
