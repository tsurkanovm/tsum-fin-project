<?php

/**
 * Grid Grid Collection.
 *
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Tsum\CashFlow\Model\ResourceModel\Storage;

use Tsum\CashFlow\Model\Storage;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = Storage::ENTITY_ID;

    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(
            'Tsum\CashFlow\Model\Storage',
            'Tsum\CashFlow\Model\ResourceModel\Storage'
        );
    }
}
