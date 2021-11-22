<?php

namespace Tsum\CashFlow\Model\ResourceModel\Transfer;

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
        $this->_init('Tsum\CashFlow\Model\Transfer', 'Tsum\CashFlow\Model\ResourceModel\Transfer');
    }
}
