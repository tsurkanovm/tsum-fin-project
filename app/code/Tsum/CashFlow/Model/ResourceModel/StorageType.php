<?php

namespace Tsum\CashFlow\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class StorageType extends AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('tsum_storage_type', 'entity_id');
    }
}
