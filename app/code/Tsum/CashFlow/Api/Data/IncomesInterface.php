<?php

namespace Tsum\CashFlow\Api\Data;

interface IncomesInterface
{
    public const ENTITY_ID = 'cf_incomes_id';
    public const STORAGE_ID         = 'storage_id';
    public const CF_ITEM_ID = 'cf_item_id';
    public const PROJECT_ID   = 'project_id';
    public const IS_ACTIVE     = 'is_active';
    public const TYPE_ID          = 'type_id';
    public const COMMENTARY          = 'commentary';
    public const TOTAL          = 'total';
    public const CURRENCY          = 'currency';
    public const REGISTRATION_TIME          = 'registration_time';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    public function getStorageId(): ?int;

    public function getCfItemId(): ?int;

    public function getProjectId(): ?int;

    public function getTypeId(): int;

    public function isActive(): ?bool;

    public function getCommentary(): ?string;

    public function getTotal(): ?float;

    public function getCurrency(): ?string;

    public function getRegistrationTime(): ?string;

    public function setId(int $id);

    public function setStorageId(int $storageId);

    public function setCfItemId(int $cfItemId);

    public function setTypeId(int $typeId);

    public function setProjectId(?int $projectId);

    public function setIsActive(bool|int $isActive);

    public function setCommentary(?string $commentary);

    public function setCurrency(string $currency);

    public function setTotal(float $total);

    public function setRegistrationTime(string $registrationTime);
}
