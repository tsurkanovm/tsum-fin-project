<?php
namespace Tsum\CashFlow\Model\ResourceModel;

/**
 *  mysql resource.
 */
class Storage extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * Construct.
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime       $date
     * @param string|null                                       $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
        $this->_idFieldName = \Tsum\CashFlow\Model\Storage::ENTITY_ID;
    }

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('tsum_cf_storage', $this->_idFieldName);
    }
}
