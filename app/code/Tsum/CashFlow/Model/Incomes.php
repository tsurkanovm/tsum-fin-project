<?php

namespace Tsum\CashFlow\Model;

use \Magento\Framework\Model\AbstractModel;

class Incomes extends AbstractModel
{
    const ENTITY_ID = 'cf_incomes_id';

    public function _construct()
    {
        $this->_init('Tsum\CashFlow\Model\ResourceModel\Incomes');
    }
}
