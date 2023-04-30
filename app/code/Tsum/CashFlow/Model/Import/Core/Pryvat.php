<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Import\Core;

use Magento\ImportExport\Model\Import\Entity\AbstractEntity;

class Pryvat extends AbstractEntity
{
    const ENTITY_CODE = 'pryvat';
    const TABLE = 'tsum_cf_incomes';
    const ENTITY_ID_COLUMN = 'cf_incomes_id';

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
     */
    public function validateRow(array $rowData, $rowNum)
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
    protected function _importData()
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

                //$this->countItemsCreated += (int) !isset($row[static::ENTITY_ID_COLUMN]);
            }

            //$this->saveEntityFinish($entityList);
        }
    }

//    private function saveEntityFinish(array $entityData): bool
//    {
//        if ($entityData) {
//            $tableName = $this->connection->getTableName(static::TABLE);
//            $rows = [];
//
//            foreach ($entityData as $entityRows) {
//                foreach ($entityRows as $row) {
//                    $rows[] = $row;
//                }
//            }
//
//            if ($rows) {
//                $this->connection->insertOnDuplicate($tableName, $rows, $this->getAvailableColumns());
//
//                return true;
//            }
//
//            return false;
//        }
//    }
}
