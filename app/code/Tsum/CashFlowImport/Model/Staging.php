<?php

namespace Tsum\CashFlowImport\Model;

use Magento\Framework\Model\AbstractModel;
use Tsum\CashFlowImport\Api\Data\StagingInterface;

class Staging extends AbstractModel implements StagingInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'tsum_cf_staging';

    public function _construct()
    {
        $this->_init(ResourceModel\Staging::class);
    }

    public function getStorageId(): ?int
    {
        return $this->getData(self::STORAGE_ID);
    }

    public function getCfItemId(): ?int
    {
        return $this->getData(self::CF_ITEM_ID);
    }

    public function getProjectId(): ?int
    {
        return $this->getData(self::PROJECT_ID);
    }

    public function getTypeId(): int
    {
        return (int)$this->getData(self::TYPE_ID);
    }

    public function isActive(): ?bool
    {
        return $this->getData(self::IS_ACTIVE);
    }

    public function getCommentary(): ?string
    {
        return $this->getData(self::COMMENTARY);
    }

    public function getTotal(): ?float
    {
        return $this->getData(self::TOTAL);
    }

    public function getCurrency(): ?string
    {
        return $this->getData(self::CURRENCY);
    }

    public function getRegistrationTime(): ?string
    {
        return $this->getData(self::REGISTRATION_TIME);
    }

    public function setStorageId(int $storageId): self
    {
        return $this->setData(self::STORAGE_ID, $storageId);
    }

    public function setCfItemId(int $cfItemId): self
    {
        return $this->setData(self::CF_ITEM_ID, $cfItemId);
    }

    public function setTypeId(int $typeId): self
    {
        return $this->setData(self::TYPE_ID, $typeId);
    }

    public function setProjectId(?int $projectId): self
    {
        return $this->setData(self::PROJECT_ID, $projectId);
    }

    public function setIsActive(bool|int $isActive): self
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    public function setCommentary(?string $commentary): self
    {
        return $this->setData(self::COMMENTARY, $commentary);
    }

    public function setCurrency(string $currency): self
    {
        return $this->setData(self::CURRENCY, $currency);
    }

    public function setTotal(float $total): self
    {
        return $this->setData(self::TOTAL, $total);
    }

    public function setRegistrationTime(string $registrationTime): self
    {
        return $this->setData(self::REGISTRATION_TIME, $registrationTime);
    }
}
