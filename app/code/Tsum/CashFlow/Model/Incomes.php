<?php

namespace Tsum\CashFlow\Model;

use \Magento\Framework\Model\AbstractModel;

class Incomes extends AbstractModel
{
    /**
     * Initialize resource model
     * @return void
     */
    public function _construct()
    {
        $this->_init('Tsum\CashFlow\Model\ResourceModel\Incomes');
    }
}
