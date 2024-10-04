<?php

namespace Tsum\Digits\Api\Data;

/**
 * Interface ResultInterface
 * @package Tsum\Digits\Api\Data
 */
interface ResultInterface
{
    public const ENTITY_ID     = 'entity_id';
    public const CUSTOMER_ID   = 'customer_id';
    public const CREATION_TIME = 'creation_time';
    public const HITS          = 'hits';
    public const SIZE          = 'size';
    public const TIME          = 'time';

    /**
     * @return int
     */
    public function getHits() : int;

    /**
     * @return int
     */
    public function getRate() : int;

    /**
     * @return string
     */
    public function getCustomer() : string;

    /**
     * @return int
     */
    public function getCustomerId() : int;

    public function getCreationTime() : string;

    /**
     * @return int
     */
    public function getTime() : int;

    /**
     * @return int
     */
    public function getSize() : int;

    /**
     * @param int $customerId
     * @return ResultInterface
     */
    public function setCustomerId(int $customerId) : ResultInterface;

    /**
     * @param int $time
     * @return ResultInterface
     */
    public function setTime(int $time) : ResultInterface;

    /**
     * @param int $hits
     * @return ResultInterface
     */
    public function setHits(int $hits) : ResultInterface;

    /**
     * @param int $size
     * @return ResultInterface
     */
    public function setSize(int $size) : ResultInterface;
}
