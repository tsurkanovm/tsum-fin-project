<?php

namespace Tsum\CashFlow\Model;

use Magento\Framework\Model\AbstractModel;
use Tsum\CashFlow\Api\Data\IncomesInterface;

class Incomes extends AbstractModel implements IncomesInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'tsum_cf_incomes';

    public function _construct()
    {
        $this->_init('Tsum\CashFlow\Model\ResourceModel\Incomes');
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

    public function getTypeId(): ?int
    {
        return $this->getData(self::TYPE_ID);
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

    public function setStorageId(int $storageId)
    {
        return $this->setData(self::STORAGE_ID, $storageId);
    }

    public function setCfItemId(int $cfItemId)
    {
        return $this->setData(self::CF_ITEM_ID, $cfItemId);
    }

    public function setTypeId(int $typeId)
    {
        return $this->setData(self::TYPE_ID, $typeId);
    }

    public function setProjectId(?int $projectId)
    {
        return $this->setData(self::PROJECT_ID, $projectId);
    }

    public function setIsActive(bool|int $isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    public function setCommentary(?string $commentary)
    {
        return $this->setData(self::COMMENTARY, $commentary);
    }

    public function setCurrency(string $currency)
    {
        return $this->setData(self::CURRENCY, $currency);
    }

    public function setTotal(float $total)
    {
        return $this->setData(self::TOTAL, $total);
    }

    public function setRegistrationTime(string $registrationTime)
    {
        return $this->setData(self::REGISTRATION_TIME, $registrationTime);
    }
}
