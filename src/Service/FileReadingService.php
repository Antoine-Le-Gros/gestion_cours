<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\CourseTitle;
use App\Entity\HourlyVolume;
use App\Entity\Module;
use App\Entity\Semester;
use App\Entity\Week;
use App\Entity\Year;
use App\Repository\CourseTitleRepository;
use App\Repository\ModuleRepository;
use App\Repository\TagRepository;
use App\Repository\TypeCourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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

    /**
     * @param string[] $row
     * @param Week[]   $weeks
     */
    public function createHoursVolumesFromRow(int $firstWeekNumber, int $firstWeekIndex, array $row, Course $course, array $weeks): bool
    {
        $volumes = [];
        for ($i = 0; $i < count($row) - $firstWeekIndex; ++$i) {
            $weekNumber = $firstWeekNumber + $i;
            if ($weekNumber > 52) {
                $weekNumber -= 52;
            }
            $weekIndex = $firstWeekIndex + $i;
            $hours = (float) $row[$weekIndex];
            if (0 != $hours) {
                $volume = new HourlyVolume();
                $volume->setVolume($hours);
                $volume->setWeek($weeks[$weekNumber]);
                $volumes[] = $volume;
                $course->addHourlyVolume($volume);
            }
        }

        foreach ($volumes as $volume) {
            $this->em->persist($volume);
        }

        return true;
    }

    public function useDocument(Spreadsheet $document, Year $year): void
    {
        $document = $document->getAllSheets();
        for ($i = 0; $i < count($document); ++$i) {
            $semester = new Semester();
            $semester->setYear($year);
            $semester->setNumber($i + 1);
            $this->em->persist($semester);
            $this->em->flush();
            $this->usePage($document[$i], $semester);
            $this->em->persist($semester);
            $this->em->flush();
        }
    }

    public function usePage(Worksheet $page, Semester $semester): void
    {
        $page = $page->toArray();
        $module = [];
        $courseTitle = null;
        $firstRowData = $this->initiateFirstLineInformation($page[0], $semester);
        $page = array_slice($page, 1);
        foreach ($page as $row) {
            $modules = $this->createModuleFromRow($row, $semester, $module);
            $courseTitle = $this->createCourseTitleFromRow($row, $modules, $courseTitle);
            $course = $this->createCourseFromRow($row, $courseTitle);
            $this->createHoursVolumesFromRow($firstRowData['firstWeekNumber'], $firstRowData['firstWeekIndex'], $row, $course, $firstRowData['weeks']);
        }
    }

    /**
     * @param string[] $row
     *
     * @return mixed[]
     */
    public function initiateFirstLineInformation(array $row, Semester $semester): array
    {
        $firstLineInformation = [];
        $j = 0;
        while (!is_numeric($row[$j]) && $j < count($row) - 1) {
            ++$j;
        }

        $firstLineInformation['firstWeekNumber'] = (int) $row[$j];
        $firstLineInformation['firstWeekIndex'] = $j;

        while ($j < count($row) && is_numeric($row[$j])) {
            $week = new Week();
            $week->setNumber((int) $row[$j]);
            $week->setSemesters($semester);
            $this->em->persist($week);
            $firstLineInformation['weeks'][$row[$j]] = $week;
            ++$j;
        }

        return $firstLineInformation;
    }

    /**
     * @param string[] $row
     * @param Module[] $module
     *
     * @return Module[]
     */
    public function createModuleFromRow(array $row, Semester $semester, array $module): array
    {
        if (null == $row[0]) {
            return $module;
        }

        $moduleNames = $this->parseModuleName($row[0]);
        $modules = [];

        foreach ($moduleNames as $moduleName) {
            $module = $this->MRepository->findOneBy(['name' => $moduleName, 'semester' => $semester]) ?? new Module();
            if (!$module->getId()) {
                $module->setName($moduleName);
                $module->setSemester($semester);
                $this->em->persist($module);
                $this->em->flush();
            }
            $modules[] = $module;
        }

        return $modules;
    }

    /**
     * @param string[] $row
     * @param Module[] $modules
     */
    public function createCourseTitleFromRow(array $row, array $modules, ?CourseTitle $courseTitle): CourseTitle
    {
        if (null == $row[1]) {
            return $courseTitle;
        }

        $courseName = $row[1];
        $repo = $this->CTRepository;
        $course = $repo->findOneByNameAndModules($courseName, $modules) ?? new CourseTitle();

        if (!$course->getId()) {
            $course->setName($courseName);
            foreach ($modules as $module) {
                $course->addModule($module);
            }
            $this->em->persist($course);
            $this->em->flush();
        }

        $this->addTagToTitle($row[self::TAG_COLUMN], $course);

        return $course;
    }

    /**
     * @param string[] $row
     */
    public function createCourseFromRow(array $row, CourseTitle $courseTitle): Course
    {
        $course = new Course();
        $course->setCourseTitle($courseTitle);
        $course->setGroupMaxNumber((int) $row[self::GROUP_MAX_NUMBER_COLUMN]);
        $course->setTypeCourse($this->TCRepository->findOneBy(['name' => $row[self::TYPE_COLUMN]]));
        $course->setSAESupport($row[self::SAE_SUPPORT_COLUMN]);
        $this->em->persist($course);
        $this->em->flush();

        return $course;
    }

    public function addTagToTitle(string $tags, CourseTitle $courseTitle): CourseTitle
    {
        $tags = $this->parseModuleName($tags);
        foreach ($tags as $tag) {
            $tag = $this->TRepository->findOrCreateOne($tag);
            $courseTitle->addTag($tag);
        }

        return $courseTitle;
    }
}
