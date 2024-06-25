<?php

namespace Tsum\CashFlowImport\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Staging extends AbstractDb
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init('tsum_cf_staging', \Tsum\CashFlowImport\Model\Staging::ENTITY_ID);
    }
}
