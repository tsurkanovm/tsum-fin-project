<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Import;



use Magento\Framework\File\Csv;

class Ones
{

    public function __construct(
        private readonly Csv $csvReader
    )
    {
    }

    public function importByType(?int $type)
    {
        return $type ? $this->importTransfers() : $this->importIncomes();
    }

    public function importIncomes()
    {
        //$this->csvReader->getData();
    }

    public function importTransfers()
    {

    }
}
