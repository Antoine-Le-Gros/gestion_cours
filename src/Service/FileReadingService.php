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
        for ($i = 0; $i < count($row) - $firstWeekIndex; ++$i) {
            $weekNumber = $firstWeekNumber + $i;
            if ($weekNumber > 52) {
                $weekNumber = $weekNumber - 52;
            }
            $weekIndex = $firstWeekIndex + $i;
            $hours = (float) $row[$weekIndex];
            if (0 != $hours) {
                $volume = new HourlyVolume();
                $volume->setVolume((float) $row[$weekIndex]);
                $volume->setWeek($weeks[$weekNumber]);
                $this->em->persist($volume);
                $course->addHourlyVolume($volume);
                $this->em->persist($course);
            }
        }

        return true;
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

        $moduleName = $row[0];
        $modules = [];
        $moduleNames = $this->parseModuleName($moduleName);
        $repo = $this->MRepository;
        foreach ($moduleNames as $moduleName) {
            if (null === $repo->findOneBy(['name' => $moduleName, 'semester' => $semester])) {
                $module = new Module();
                $module->setName($moduleName);
                $module->setSemester($semester);
                $this->em->persist($module);
                $this->em->flush();
            } else {
                $module = $repo->findOneBy(['name' => $moduleName, 'semester' => $semester]);
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
        if (null === $repo->findOneByNameAndModules($courseName, $modules)) {
            $course = new CourseTitle();
            $course->setName($courseName);
            foreach ($modules as $module) {
                $course->addModule($module);
            }
            $this->em->persist($course);
            $this->em->flush();
        } else {
            $course = $repo->findOneByNameAndModules($courseName, $modules);
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
