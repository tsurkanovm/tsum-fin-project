<?php

namespace Tsum\CashFlowImport\Model\Pryvat;

use Tsum\CashFlowImport\Api\FileReaderInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;

class XlsReader implements FileReaderInterface
{
    public const FIRST_ROW = 3;
    public function read(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = [];

        foreach ($worksheet->getRowIterator(self::FIRST_ROW) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            if ($rowData[0]) {
                $rows[] = $rowData;
            }

        }

        return $rows;
    }
}
