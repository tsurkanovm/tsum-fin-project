<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Import;



use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Csv;

class Ones
{
    public const SOURCE_FOLDER = 'ones';
    public const INCOME_FILE_NAME = 'incomes.csv';
    public const TRANSFER_FILE_NAME = 'transfers.csv';

    public function __construct(
        private readonly Csv $csvReader,
        private readonly DirectoryList $directoryList
    )
    {
    }

    public function importByType(?int $type)
    {
        $this->csvReader->setDelimiter(';');
        $filePath = $this->getOnesFilesPath() . self::INCOME_FILE_NAME;
        $dataFromFile = $this->csvReader->getData($filePath);
        unset($dataFromFile[0]);
        foreach ($dataFromFile as $rowData) {
            $type ? $this->importTransfers($rowData) : $this->importIncomes($rowData);
        }
    }

    public function importIncomes(array $rowData)
    {
        $storageId = $this->getStorage($rowData);
        $cfItemId = $this->getCfItem($rowData);
        $projectId = $this->getProject($rowData);
        // create income
    }

    public function importTransfers()
    {

    }

    private function getOnesFilesPath() : string
    {
        return $this->directoryList->getPath(DirectoryList::VAR_DIR) . DIRECTORY_SEPARATOR .
            self::SOURCE_FOLDER . DIRECTORY_SEPARATOR;
    }
}
