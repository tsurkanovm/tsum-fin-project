<?php

namespace Tsum\CashFlowImport\Api\Data;

interface StagingInterface
{
    public const ENTITY_ID = 'cf_incomes_id';
    public const STORAGE_ID = 'storage_id';
    public const CF_ITEM_ID = 'cf_item_id';
    public const PROJECT_ID = 'project_id';
    public const IS_ACTIVE = 'is_active';
    public const TYPE_ID = 'type_id';
    public const COMMENTARY = 'commentary';
    public const TOTAL = 'total';
    public const CURRENCY = 'currency';
    public const REGISTRATION_TIME = 'registration_time';

    /**
     * Get ID
     *
     * @return int|null
     * @phpstan-ignore-next-line
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

    /** @phpstan-ignore-next-line */
    public function setId($id);

    public function setStorageId(int $storageId): self;

    public function setCfItemId(int $cfItemId): self;

    public function setTypeId(int $typeId): self;

    public function setProjectId(?int $projectId): self;

    public function setIsActive(bool|int $isActive): self;

    public function setCommentary(?string $commentary): self;

    public function setCurrency(string $currency): self;

    public function setTotal(float $total): self;

    public function setRegistrationTime(string $registrationTime): self;
}
