<?php

namespace Tsum\CashFlow\Model;

use \Magento\Framework\Model\AbstractModel;

class CfItem extends AbstractModel
{
    const ENTITY_ID     = 'cf_item_id';
    const TITLE         = 'title';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME   = 'update_time';
    const IS_ACTIVE     = 'is_active';
    const MOVE          = 'move';

    /**
     * Initialize resource model
     * @return void
     */
    public function _construct()
    {
        $this->_init('Tsum\CashFlow\Model\ResourceModel\CfItem');
    }

    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Get Title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData('title');
    }

    /**
     * Get creation time
     *
     * @return string
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * Get update time
     *
     * @return string
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive()
    {
        return (bool)$this->getData(self::IS_ACTIVE);
    }

    /**
     * Get move
     *
     * @return string
     */
    public function getMove()
    {
        return $this->getData(self::MOVE);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
    /**
     * @param string $entityId
     * @return $this
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return $this
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setMove($type)
    {
        return $this->setData(self::MOVE, $type);
    }
}
