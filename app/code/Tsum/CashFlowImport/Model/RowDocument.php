<?php

namespace Tsum\CashFlowImport\Model;

use Magento\Framework\DataObject;

class RowDocument extends DataObject
{
    public const CATEGORY_KEY = 'category';
    public const COMMENTARY_KEY = 'commentary';
    public const TOTAL_KEY = 'total';
    public const REGISTRATION_TIME_KEY = 'registration_time';
    public const STORAGE_ID_KEY = 'storage_id';

    public function getCategory(): string
    {
        return $this->getData(self::CATEGORY_KEY);
    }

    public function getCommentary(): string
    {
        return $this->getData(self::COMMENTARY_KEY);
    }

    public function getTotal(): float
    {
        return (float)$this->getData(self::TOTAL_KEY);
    }

    public function getRegistrationTime(): string
    {
        return $this->getData(self::REGISTRATION_TIME_KEY);
    }

    public function getStorageId(): int
    {
        return (int)$this->getData(self::STORAGE_ID_KEY);
    }

    public function setCategory(string $category): self
    {
        return $this->setData(self::CATEGORY_KEY, $category);
    }

    public function setCommentary(string $commentary): self
    {
        return $this->setData(self::COMMENTARY_KEY, $commentary);
    }

    public function setTotal(float $total): self
    {
        return $this->setData(self::TOTAL_KEY, $total);
    }

    public function setRegistrationTime(string $registrationTime): self
    {
        return $this->setData(self::REGISTRATION_TIME_KEY, $registrationTime);
    }

    public function setStorageId(int|string $storageId): self
    {
        return $this->setData(self::STORAGE_ID_KEY, (int)$storageId);
    }
}
