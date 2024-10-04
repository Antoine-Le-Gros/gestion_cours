<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Reader\IReader;

class FileReadingService
{
    private IReader $reader;

    public function __construct()
    {
        $this->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
    }

    public function getReader(): IReader
    {
        return $this->reader;
    }
}
