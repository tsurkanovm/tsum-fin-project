<?php

namespace Tsum\CashFlow\Model;

use \Magento\Framework\Model\AbstractModel;

class Transfer extends AbstractModel
{
    const ENTITY_ID = 'cf_transfer_id';

    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(ResourceModel\Transfer::class);
    }
}
