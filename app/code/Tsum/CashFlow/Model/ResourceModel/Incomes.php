<?php

namespace Tsum\CashFlow\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Incomes extends AbstractDb
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init('tsum_cf_incomes', \Tsum\CashFlow\Model\Incomes::ENTITY_ID);
    }
}
