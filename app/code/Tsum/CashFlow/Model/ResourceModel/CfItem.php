<?php

namespace Tsum\CashFlow\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Tsum\CashFlow\Api\Data\CfItemInterface;

class CfItem extends AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct(): void
    {
        $this->_init('tsum_cf_item', CfItemInterface::ENTITY_ID);
    }
}
