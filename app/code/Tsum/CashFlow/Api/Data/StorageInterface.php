<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tsum\CashFlow\Api\Data;

/**
 * CashFlow storage interface.
 * @api
 * @since 100.0.2
 */
interface StorageInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const TITLE         = 'title';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME   = 'update_time';
    const IS_ACTIVE     = 'is_active';
    const TYPE          = 'type';

    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Get type
     *
     * @return string|null
     */
    public function getType();

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Tsum\CashFlow\Api\Data\StorageInterface
     */
    public function setId($id);

    /**
     * Set title
     *
     * @param string $title
     * @return \Tsum\CashFlow\Api\Data\StorageInterface
     */
    public function setTitle($title);
    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \Tsum\CashFlow\Api\Data\StorageInterface
     */
    public function setIsActive($isActive);

    /**
     * Set title
     *
     * @param string $type
     * @return \Tsum\CashFlow\Api\Data\StorageInterface
     */
    public function setType($type);
}
