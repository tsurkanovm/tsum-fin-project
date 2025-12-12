<?php

namespace Tsum\CashFlow\Api\Data;

interface TransferInterface extends RegistrationDocumentInterface
{
    public const ENTITY_ID     = 'cf_transfer_id';
    public const STORAGE_ID    = 'storage_id_out';
    public const IN_STORAGE_ID = 'storage_id_in';
    public const COMMENTARY    = 'commentary';
    public const TOTAL         = 'total_out';
    public const IN_TOTAL      = 'total_in';
    public const CURRENCY      = 'currency_out';
    public const IN_CURRENCY   = 'currency_in';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    public function getStorage(): ?int;

    public function getStorageIn(): ?int;

    public function getCommentary(): ?string;

    public function getTotal(): ?float;

    public function getTotalIn(): ?float;

    public function getCurrency(): ?string;

    public function getCurrencyIn(): ?string;

    public function setId(int $id);

    public function setStorage(int $storageOutId);

    public function setStorageIn(int $storageInId);

    public function setCommentary(?string $commentary);

    public function setCurrency(string $currency);

    public function setCurrencyIn(string $currencyIn);

    public function setTotal(float $total);

    public function setTotalIn(float $totalIn);
}
