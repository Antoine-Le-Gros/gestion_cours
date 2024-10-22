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

    private EntityManagerInterface $em;
    private CourseTitleRepository $CTRepository;
    private ModuleRepository $MRepository;
    private TypeCourseRepository $TCRepository;
    private TagRepository $TRepository;

    public function __construct(EntityManagerInterface $em, CourseTitleRepository $CTRepository, ModuleRepository $MRepository, TypeCourseRepository $TCRepository, TagRepository $TRepository)
    {
        $this->em = $em;
        $this->CTRepository = $CTRepository;
        $this->MRepository = $MRepository;
        $this->TCRepository = $TCRepository;
        $this->TRepository = $TRepository;
        $this->reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $this->reader->setReadEmptyCells(false);
    }

    public function getReader(): IReader
    {
        return $this->reader;
    }

    /**
     * @return string[]
     */
    public function parseModuleName(string $modules): array
    {
        $modulesP = preg_split('#([/\-,])#', $modules);
        $modulesP = array_map(function ($module) {
            return trim($module);
        }, $modulesP);

        return $modulesP;
    }
}
