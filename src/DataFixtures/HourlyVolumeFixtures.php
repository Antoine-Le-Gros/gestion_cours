<?php

namespace App\DataFixtures;

use App\Factory\HourlyVolumeFactory;
use App\Repository\CourseRepository;
use App\Repository\WeekRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class HourlyVolumeFixtures extends Fixture implements DependentFixtureInterface
{
    private CourseRepository $courseRepository;
    private WeekRepository $weekRepository;

    public function __construct(CourseRepository $courseRepository, WeekRepository $weekRepository)
    {
        $this->courseRepository = $courseRepository;
        $this->weekRepository = $weekRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $courses = $this->courseRepository->findAll();
        $weeks = $this->weekRepository->findAll();
        foreach ($courses as $course) {
            foreach ($weeks as $week) {
                HourlyVolumeFactory::createOne(['course' => $course, 'week' => $week]);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CourseFixtures::class,
            WeekFixtures::class,
        ];
    }
}
