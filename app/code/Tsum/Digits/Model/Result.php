<?php

namespace Tsum\Digits\Model;

use \Magento\Framework\Model\AbstractModel;
use Tsum\Digits\Api\Data\ResultInterface;

class Result extends AbstractModel implements ResultInterface
{
    /**
     * Initialize resource model
     * @return void
     */
    public function _construct()
    {
        $this->_init('Tsum\Digits\Model\ResourceModel\Result');
    }

    public function getHits() : int
    {
        return $this->getData(self::HITS);
    }

    public function getRate(): int
    {
        return $this->getHits() * $this->getData(self::TIME);
    }

    public function getCustomer(): string
    {
        return 'Guest';
    }

    public function getCustomerId() : int
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    public function getTime() : int
    {
        return $this->getData(self::TIME);
    }

    public function getSize() : int
    {
        return $this->getData(self::SIZE);
    }

    public function setCustomerId(int $customerId) : ResultInterface
    {
        $this->setData(self::CUSTOMER_ID, $customerId);

        return $this;
    }

    public function setTime(int $time) : ResultInterface
    {
        $this->setData(self::TIME, $time);

        return $this;
    }

    public function setHits(int $hits) : ResultInterface
    {
        $this->setData(self::HITS, $hits);

        return $this;
    }

    public function setSize(int $size) : ResultInterface
    {
        $this->setData(self::SIZE, $size);

        return $this;
    }
}
