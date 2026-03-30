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
     * @return int|null
     */
    public function getId();

    /**
     * @return int|null
     */
    public function getStorage(): ?int;

    /**
     * @return int|null
     */
    public function getStorageIn(): ?int;

    /**
     * @return string|null
     */
    public function getCommentary(): ?string;

    /**
     * @return float|null
     */
    public function getTotal(): ?float;

    /**
     * @return float|null
     */
    public function getTotalIn(): ?float;

    /**
     * @return string|null
     */
    public function getCurrency(): ?string;

    /**
     * @return string|null
     */
    public function getCurrencyIn(): ?string;

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id);

    /**
     * @param int $storageOutId
     * @return $this
     */
    public function setStorage(int $storageOutId);

    /**
     * @param int $storageInId
     * @return $this
     */
    public function setStorageIn(int $storageInId);

    /**
     * @param string|null $commentary
     * @return $this
     */
    public function setCommentary(?string $commentary);

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency(string $currency);

    /**
     * @param string $currencyIn
     * @return $this
     */
    public function setCurrencyIn(string $currencyIn);

    /**
     * @param float $total
     * @return $this
     */
    public function setTotal(float $total);

    /**
     * @param float $totalIn
     * @return $this
     */
    public function setTotalIn(float $totalIn);
}
