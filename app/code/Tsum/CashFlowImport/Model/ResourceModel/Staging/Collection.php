<?php

namespace Tsum\CashFlowImport\Model\ResourceModel\Staging;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Tsum\CashFlowImport\Model\Staging;

class Collection extends AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(Staging::class, \Tsum\CashFlowImport\Model\ResourceModel\Staging::class);
    }
}
