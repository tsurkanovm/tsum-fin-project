<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Import\Core;

use Magento\ImportExport\Model\Import\Entity\AbstractEntity;

// it is not working for now, just draft version, possibly will use in the future
class Pryvat extends AbstractEntity
{
    public const ENTITY_CODE = 'pryvat';
    public const TABLE = 'tsum_cf_incomes';
    public const ENTITY_ID_COLUMN = 'cf_incomes_id';

    protected $logInHistory = true;

    /**
     * @inheritDoc
     */
    public function getEntityTypeCode(): string
    {
        return static::ENTITY_CODE;
    }

    /**
     * @inheritDoc
     * @param mixed[] $rowData
     */
    public function validateRow(array $rowData, $rowNum): bool
    {
        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }

        $this->_validatedRows[$rowNum] = true;

        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }

    /**
     * @inheritDoc
     */
    protected function _importData(): bool
    {
        $rows = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityList = [];

            foreach ($bunch as $rowNum => $row) {
                if (!$this->validateRow($row, $rowNum)) {
                    continue;
                }

                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);

                    continue;
                }

                $this->countItemsCreated += (int) !isset($row[static::ENTITY_ID_COLUMN]);
            }

            return $this->saveEntityFinish($entityList);
        }

        return false;
    }

    /**
     * @param mixed[] $entityData
     * @return bool
     */
    private function saveEntityFinish(array $entityData): bool
    {
        $tableName = $this->_connection->getTableName(static::TABLE);
        $rows = [];

        foreach ($entityData as $entityRows) {
            foreach ($entityRows as $row) {
                $rows[] = $row;
            }
        }

        if ($rows) {
            $this->_connection->insertOnDuplicate($tableName, $rows, $this->getAvailableColumns());

            return true;
        }

        return false;
    }
}
