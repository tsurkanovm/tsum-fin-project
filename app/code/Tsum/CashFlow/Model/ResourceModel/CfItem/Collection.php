<?php

namespace Tsum\CashFlow\Model\ResourceModel\CfItem;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Tsum\CashFlow\Model\CfItem;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = CfItem::ENTITY_ID;

    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(
            'Tsum\CashFlow\Model\CfItem',
            'Tsum\CashFlow\Model\ResourceModel\CfItem'
        );
    }
}
