<?php

namespace Tsum\CashFlowImport\Api;

interface FileReaderInterface
{
    public function read(string $filePath): array;
}
