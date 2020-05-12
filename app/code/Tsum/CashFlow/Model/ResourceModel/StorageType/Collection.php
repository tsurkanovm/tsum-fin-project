<?php
declare(strict_types=1);
namespace Tsum\CashFlow\Model\ResourceModel\StorageType;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct() : void
    {
        $this->_init('Tsum\CashFlow\Model\StorageType', 'Tsum\CashFlow\Model\ResourceModel\StorageType');
    }

    public function toOptionArray() : array
    {
        return $this->_toOptionArray('entity_id', 'title');
    }
}
