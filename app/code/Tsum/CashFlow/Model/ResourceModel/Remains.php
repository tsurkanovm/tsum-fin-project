<?php

namespace Tsum\CashFlow\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Remains extends AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('tsum_cf_remains', 'cf_remains_id');
    }
}

