<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Import;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Csv;
use Tsum\CashFlow\Api\CfItemRepositoryInterface;
use Tsum\CashFlow\Api\Data\CfItemInterface;
use Tsum\CashFlow\Api\Data\CfItemInterfaceFactory;
use Tsum\CashFlow\Api\Data\IncomesInterface;
use Tsum\CashFlow\Api\Data\IncomesInterfaceFactory;
use Tsum\CashFlow\Api\Data\StorageInterface;
use Tsum\CashFlow\Api\Data\StorageInterfaceFactory;
use Tsum\CashFlow\Api\IncomesRepositoryInterface;
use Tsum\CashFlow\Api\StorageRepositoryInterface;
use Tsum\CashFlow\Model\Config;

class Ones
{
    public const SOURCE_FOLDER = 'ones';
    public const INCOME_FILE_NAME = 'incomes.csv';
    public const TRANSFER_FILE_NAME = 'transfers.csv';

    public const CURRENCY_MAP = [
        980 => 'UAH',
        840 => 'USD',
        978 => 'EUR',
    ];

    public function __construct(
        private readonly Csv $csvReader,
        private readonly DirectoryList $directoryList,
        private readonly StorageRepositoryInterface $storageRepo,
        private readonly CfItemRepositoryInterface $itemRepo,
        private readonly IncomesRepositoryInterface $incomesRepo,
        private readonly StorageInterfaceFactory $storageFactory,
        private readonly CfItemInterfaceFactory $itemFactory,
        private readonly IncomesInterfaceFactory $incomesFactory
    )
    {
    }

    public function importByType(?int $type): void
    {
        $this->csvReader->setDelimiter(';');
        $filePath = $this->getOnesFilesPath() . self::INCOME_FILE_NAME;
        $dataFromFile = $this->csvReader->getData($filePath);
        unset($dataFromFile[0]);
        foreach ($dataFromFile as $num => $rowData) {
            $type ? $this->importTransfers($rowData) : $this->importIncomes($rowData);
//            if ($num > 100) {
//                break;
//            }
        }
    }

    public function importIncomes(array $rowData): void
    {
        $storageId = $this->getStorageId($rowData);
        $cfItemId = $this->getCfItemId($rowData);

        $registrationDate = (string)$rowData[IncomeFormat::REGISTRATION_ROW];
        $projectId = (int)$rowData[IncomeFormat::PROJECT_ROW];
        $currencyCode = self::CURRENCY_MAP[trim($rowData[IncomeFormat::CURRENCY_ROW])];
        $type = (bool)$rowData[IncomeFormat::TYPE_ROW];

        // create income
        /** @var IncomesInterface $income */
        $income = $this->incomesFactory->create();
        $income->setIsActive(1);
        $income->setRegistrationTime($registrationDate);
        $income->setCurrency($currencyCode);
        $income->setCfItemId($cfItemId);
        if ($projectId) {
            $income->setProjectId($projectId);
        }
        $income->setStorageId($storageId);
        $income->setTypeId((int)$type);
        $income->setTotal($this->prepareTotalValue($rowData[IncomeFormat::TOTAL_ROW]));

        $this->incomesRepo->save($income);
    }

    public function importTransfers()
    {

    }

    private function getOnesFilesPath() : string
    {
        return $this->directoryList->getPath(DirectoryList::VAR_DIR) . DIRECTORY_SEPARATOR .
            self::SOURCE_FOLDER . DIRECTORY_SEPARATOR;
    }

    private function getStorageId(array $rowData): int
    {
        $onesId = (int)$rowData[IncomeFormat::STORAGE_ID_ROW];
        if ($onesId === 2) { // Vika privat, needs to convert (since 2019) to Raff storage (15)
            $regDate = strtotime($rowData[IncomeFormat::REGISTRATION_ROW]);
            //$regDate = new \DateTime($regDateString);
            $raffDate = strtotime('2019-01-01');
            if ($regDate < $raffDate) {
                $onesId = 15;
            }
        }
        if ($storageId = $this->storageRepo->getIdByOnesId($onesId)) {
            return $storageId;
        }

        return (int)$this->createStorage($rowData)->getId();
    }

    private function createStorage(array $rowData): StorageInterface
    {
        $storageName = $rowData[IncomeFormat::STORAGE_ROW];
        $onesId = (int)$rowData[IncomeFormat::STORAGE_ID_ROW];

        /** @var StorageInterface $storage */
        $storage = $this->storageFactory->create();
        $storage->setTitle(trim($storageName));
        $storage->setIsActive(1);
        $storage->setType(1); // cashless for all, then manually needs to change
        $storage->setData(Config::ONES_CODE_FIELD, $onesId);
        $this->storageRepo->save($storage);

        return $storage;
    }

    private function getCfItemId(array $rowData): int
    {
        $onesId = (int)$rowData[IncomeFormat::ITEM_ID_ROW];
        if ($itemId = $this->itemRepo->getIdByOnesId($onesId)) {
            return $itemId;
        }

        return (int)$this->createCfItem($rowData)->getId();
    }

    private function createCfItem(array $rowData): CfItemInterface
    {
        $itemName = $rowData[IncomeFormat::ITEM_ROW];
        $onesId = $rowData[IncomeFormat::ITEM_ID_ROW];
        $type = (bool)$rowData[IncomeFormat::TYPE_ROW];

        /** @var CfItemInterface $cfItem */
        $cfItem = $this->itemFactory->create();
        $cfItem->setTitle(trim($itemName));
        $cfItem->setIsActive(1);
        $cfItem->setMove((int)$type);
        $cfItem->setData(Config::ONES_CODE_FIELD, $onesId);
        $this->itemRepo->save($cfItem);

        return $cfItem;
    }

    private function prepareTotalValue(string $rawTotal): float
    {
        return (float)preg_replace('/[^0-9,\-]/', '', $rawTotal);
    }
}
