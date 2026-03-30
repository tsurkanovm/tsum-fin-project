<?php

namespace Tsum\CashFlow\Api\Data;

interface IncomesInterface extends RegistrationDocumentInterface
{
    public const ENTITY_ID = 'cf_incomes_id';
    public const STORAGE_ID = 'storage_id';
    public const CF_ITEM_ID = 'cf_item_id';
    public const PROJECT_ID = 'project_id';

    public const TYPE_ID = 'type_id';
    public const COMMENTARY = 'commentary';
    public const TOTAL = 'total';
    public const CURRENCY = 'currency';

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @return int|null
     */
    public function getStorageId(): ?int;

    /**
     * @return int|null
     */
    public function getCfItemId(): ?int;

    /**
     * @return int|null
     */
    public function getProjectId(): ?int;

    /**
     * @return int
     */
    public function getTypeId(): int;

    /**
     * @return string|null
     */
    public function getCommentary(): ?string;

    /**
     * @return float|null
     */
    public function getTotal(): ?float;

    /**
     * @return string|null
     */
    public function getCurrency(): ?string;

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id);

    /**
     * @param int $storageId
     * @return $this
     */
    public function setStorageId(int $storageId);

    /**
     * @param int $cfItemId
     * @return $this
     */
    public function setCfItemId(int $cfItemId);

    /**
     * @param int $typeId
     * @return $this
     */
    public function setTypeId(int $typeId);

    /**
     * @param int|null $projectId
     * @return $this
     */
    public function setProjectId(?int $projectId);

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
     * @param float $total
     * @return $this
     */
    public function setTotal(float $total);
}
