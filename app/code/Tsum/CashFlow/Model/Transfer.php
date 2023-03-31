<?php

namespace Tsum\CashFlow\Model;

use \Magento\Framework\Model\AbstractModel;
use Tsum\CashFlow\Api\Data\TransferInterface;

class Transfer extends AbstractModel implements TransferInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'tsum_cf_transfer';

    public function _construct()
    {
        $this->_init(ResourceModel\Transfer::class);
    }

    public function getStorage(): ?int
    {
        return $this->getData(self::STORAGE_ID);
    }

    public function getStorageIn(): ?int
    {
        return $this->getData(self::IN_STORAGE_ID);
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

    public function getTotalIn(): ?float
    {
        return $this->getData(self::IN_TOTAL);
    }

    public function getCurrency(): ?string
    {
        return $this->getData(self::CURRENCY);
    }

    public function getCurrencyIn(): ?string
    {
        return $this->getData(self::IN_CURRENCY);
    }

    public function getRegistrationTime(): ?string
    {
        return $this->getData(self::REGISTRATION_TIME);
    }

    public function setStorage(int $storageOutId)
    {
        return $this->setData(self::STORAGE_ID, $storageOutId);
    }

    public function setStorageIn(int $storageInId)
    {
        return $this->setData(self::IN_STORAGE_ID, $storageInId);
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

    public function setCurrencyIn(string $currencyIn)
    {
        return $this->setData(self::IN_CURRENCY, $currencyIn);
    }

    public function setTotal(float $total)
    {
        return $this->setData(self::TOTAL, $total);
    }

    public function setTotalIn(float $totalIn)
    {
        return $this->setData(self::IN_TOTAL, $totalIn);
    }

    public function setRegistrationTime(string $registrationTime)
    {
        return $this->setData(self::REGISTRATION_TIME, $registrationTime);
    }
}
