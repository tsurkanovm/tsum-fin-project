<?php

namespace Tsum\CashFlow\Model;

use \Magento\Framework\Model\AbstractModel;
use Tsum\CashFlow\Api\Data\CfItemInterface;

class CfItem extends AbstractModel implements CfItemInterface
{
    public function _construct()
    {
        $this->_init('Tsum\CashFlow\Model\ResourceModel\CfItem');
    }

    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @inheirtDoc
     */
    public function getTitle():?string
    {
        return $this->getData('title');
    }

    /**
     * @inheirtDoc
     */
    public function getCreationTime():?string
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * @inheirtDoc
     */
    public function getUpdateTime():?string
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * @inheirtDoc
     */
    public function isActive():?bool
    {
        return (bool)$this->getData(self::IS_ACTIVE);
    }

    /**
     * @inheirtDoc
     */
    public function getMove()
    {
        return $this->getData(self::MOVE);
    }

    /**
     * @inheirtDoc
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * @inheirtDoc
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @@inheirtDoc
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheirtDoc
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @inheirtDoc
     */
    public function setMove($type)
    {
        return $this->setData(self::MOVE, $type);
    }
}
