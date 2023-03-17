<?php

namespace Tsum\CashFlow\Api\Data;

interface CfItemInterface
{
    const ENTITY_ID     = 'cf_item_id';
    const TITLE         = 'title';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME   = 'update_time';
    const IS_ACTIVE     = 'is_active';
    const MOVE          = 'type_id';

    const MOVE_IN_ID = 0;
    const MOVE_OUT_ID = 1;

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
    public function getTitle(): ?string;

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime(): ?string;

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime(): ?string;

    /**
     * Get move
     *
     * @return int
     */
    public function getMove();

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive(): ?bool;

    /**
     * Set ID
     *
     * @param int $id
     * @return  $self
     */
    public function setId(int $id);

    /**
     * Set title
     *
     * @param string $title
     * @return  $self
     */
    public function setTitle(string $title);

    /**
     * Set is active
     *
     * @param bool|int $isActive
     * @return  $self
     */
    public function setIsActive(bool|int $isActive);

    /**
     * Set title
     *
     * @param int $move
     * @return  $self
     */
    public function setMove(int $move);
}
