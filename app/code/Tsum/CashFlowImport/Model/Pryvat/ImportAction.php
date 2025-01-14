<?php

namespace Tsum\CashFlowImport\Model\Pryvat;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Tsum\CashFlowImport\Model\RowDocument;
use Tsum\CashFlowImport\Model\RowDocumentFactory;

class ImportAction
{
    public const CATEGORY_COLUMN = 1;
    public const COMMENTARY_COLUMN = 3;
    public const TOTAL_COLUMN = 4;

    public function __construct(
        private readonly DocumentTypeResolver $resolver,
        private readonly XlsReader            $fileReader,
        private readonly RowDocumentFactory   $rowDocumentFactory,
    ) {
    }

    /**
     * @throws CouldNotSaveException
     */
    public function execute(RequestInterface $request, string $filePath): void
    {
        $fileData = $this->fileReader->read($filePath);
        foreach ($fileData as $row) {
            $documentData = $this->prepareDocumentDataObject($request, $row);
            $this->resolver->resolve($documentData);
        }
    }

    /**
     * @param array<mixed> $row
     */
    private function prepareDocumentDataObject(RequestInterface $request, array $row): RowDocument
    {
        $rowDocument = $this->rowDocumentFactory->create();

         $rowDocument->setCategory($row[self::CATEGORY_COLUMN]);
         $rowDocument->setCommentary($row[self::COMMENTARY_COLUMN]);
         $rowDocument->setTotal($row[self::TOTAL_COLUMN]);
         $rowDocument->setRegistrationTime($request->getParam(RowDocument::REGISTRATION_TIME_KEY));
         $rowDocument->setStorageId($request->getParam(RowDocument::STORAGE_ID_KEY));

         return $rowDocument;
    }
}
