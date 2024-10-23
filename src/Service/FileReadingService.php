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
     * Method that parses a module (or tags) string into an array of strings.
     * They split at /, - and ,.
     *
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
     * Method that creates the hourly volumes from a row of the Excel file.
     * It requires the first week number and index, the row, the course and the weeks.
     *
     * @param string[] $row
     * @param Week[]   $weeks
     */
    public function createHoursVolumesFromRow(int $firstWeekNumber, int $firstWeekIndex, array $row, Course $course, array $weeks): bool
    {
        $volumes = [];
        for ($i = 0; $i < count($row) - $firstWeekIndex; ++$i) { // For Each Week
            $weekNumber = $firstWeekNumber + $i;
            if ($weekNumber > 52) { // Number can't exceed 52
                $weekNumber -= 52;
            }
            $weekIndex = $firstWeekIndex + $i;
            $hours = (float) $row[$weekIndex];
            if (0 != $hours) { // If the volume is not null and not 0
                $volume = new HourlyVolume();
                $volume->setVolume($hours);
                $volume->setWeek($weeks[$weekNumber]);
                $volumes[] = $volume;
                $course->addHourlyVolume($volume);
            }
        }

        foreach ($volumes as $volume) { // Persist as batch to optimize
            $this->em->persist($volume);
        }

        return true;
    }

    /**
     * Method that uses a document to initiate the database.
     * It requires the document and the year.
     */
    public function useDocument(Spreadsheet $document, Year $year): void
    {
        $document = $document->getAllSheets();
        for ($i = 0; $i < count($document); ++$i) { // Do the process for each page
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

    /**
     * Method that uses a page to initiate the database.
     * It requires the page and the semester.
     * It is called by useDocument.
     */
    public function usePage(Worksheet $page, Semester $semester): void
    {
        $page = $page->toArray(); // Transform the page into an array
        $module = [];
        $courseTitle = null;
        $firstRowData = $this->initiateFirstLineInformation($page[0], $semester);
        $page = array_slice($page, 1);
        foreach ($page as $row) { // Use each Line
            $modules = $this->createModuleFromRow($row, $semester, $module);
            $courseTitle = $this->createCourseTitleFromRow($row, $modules, $courseTitle);
            $course = $this->createCourseFromRow($row, $courseTitle);
            $this->createHoursVolumesFromRow($firstRowData['firstWeekNumber'], $firstRowData['firstWeekIndex'], $row, $course, $firstRowData['weeks']);
        }
    }

    /**
     * Method that initiate first line data, get weeks number and tab size.
     * It returns an array with the first week number, the first week index and the weeks.
     *
     * @param string[] $row
     *
     * @return mixed[]
     */
    public function initiateFirstLineInformation(array $row, Semester $semester): array
    {
        $firstLineInformation = [];
        $j = 0;
        while (!is_numeric($row[$j]) && $j < count($row) - 1) { // check the last line with number as a title
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
     * Method that creates a module from a row.
     *
     * @param string[] $row
     * @param Module[] $module
     *
     * @return Module[]
     */
    public function createModuleFromRow(array $row, Semester $semester, array $module): array
    {
        if (null == $row[0]) { // If the column is empty, it calls to the last module created instead of creating a new one
            return $module;
        }

        $moduleNames = $this->parseModuleName($row[0]); // Parse the module name
        $modules = [];

        foreach ($moduleNames as $moduleName) { // For each module, a module is created if not already existing
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
     * Method that creates a course title from a row.
     * It requires the row, the modules and the course title.
     *
     * @param string[] $row
     * @param Module[] $modules
     */
    public function createCourseTitleFromRow(array $row, array $modules, ?CourseTitle $courseTitle): CourseTitle
    {
        if (null == $row[1]) { // If the column is empty, it calls to the last course title created instead of creating a new one
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
     * Method that creates a course from a row.
     * It requires the row and the course title.
     *
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

    /**
     * Method that adds tags to a course title.
     * It requires the tags string (non parsed) and the course title.
     */
    public function addTagToTitle(string $tags, CourseTitle $courseTitle): CourseTitle
    {
        $tags = $this->parseModuleName($tags); // Parse the tags with the same function as modules
        foreach ($tags as $tag) {
            $tag = $this->TRepository->findOrCreateOne($tag); // Find or create the tag
            $courseTitle->addTag($tag);
        }

        return $courseTitle;
    }
}
