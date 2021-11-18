<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model;

use Magento\Framework\Model\AbstractModel;
use Tsum\CashFlow\Api\Data\StorageInterface;

/**
 * Class Storage
 * @api
 * @package Tsum\CashFlow\Model
 */
class Storage extends AbstractModel implements StorageInterface
{
    const ENTITY_ID = 'storage_id';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('Tsum\CashFlow\Model\ResourceModel\Storage');
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
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \Tsum\CashFlow\Api\Data\StorageInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
    /**
     * @param string $entityId
     * @return \Tsum\CashFlow\Api\Data\StorageInterface
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @param string $title
     * @return \Tsum\CashFlow\Api\Data\StorageInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \Tsum\CashFlow\Api\Data\StorageInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @param string $type
     * @return \Tsum\CashFlow\Api\Data\StorageInterface
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }
}
