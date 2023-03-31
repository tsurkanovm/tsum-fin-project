<?php

namespace Tsum\CashFlow\Api\Data;

interface TransferInterface
{
    public const ENTITY_ID     = 'cf_transfer_id';
    public const STORAGE_ID    = 'storage_id_out';
    public const IN_STORAGE_ID = 'storage_id_in';
    public const IS_ACTIVE     = 'is_active';
    public const COMMENTARY    = 'commentary';
    public const TOTAL         = 'total_out';
    public const IN_TOTAL      = 'total_in';
    public const CURRENCY      = 'currency_out';
    public const IN_CURRENCY   = 'currency_in';
    public const REGISTRATION_TIME = 'registration_time';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    public function getStorage(): ?int;

    public function getStorageIn(): ?int;

    public function isActive(): ?bool;

    public function getCommentary(): ?string;

    public function getTotal(): ?float;

    public function getTotalIn(): ?float;

    public function getCurrency(): ?string;

    public function getCurrencyIn(): ?string;

    public function getRegistrationTime(): ?string;

    public function setId(int $id);

    public function setStorage(int $storageOutId);

    public function setStorageIn(int $storageInId);

    public function setIsActive(bool|int $isActive);

    public function setCommentary(?string $commentary);

    public function setCurrency(string $currency);

    public function setCurrencyIn(string $currencyIn);

    public function setTotal(float $total);

    public function setTotalIn(float $totalIn);

    public function setRegistrationTime(string $registrationTime);
}
