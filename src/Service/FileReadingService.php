<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Reader\IReader;

class FileReadingService
{
    private IReader $reader;
    public const TAG_COLUMN = 2;
    public const TYPE_COLUMN = 4;
    public const GROUP_MAX_NUMBER_COLUMN = 3;
    public const SAE_SUPPORT_COLUMN = 5;

    public function __construct()
    {
        $this->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
    }

    public function getReader(): IReader
    {
        return $this->reader;
    }
}
