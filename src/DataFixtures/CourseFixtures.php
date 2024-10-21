<?php

namespace App\DataFixtures;

use App\Factory\CourseFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CourseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        CourseFactory::createMany(10);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TypeCourseFixtures::class,
            CourseTitleFixtures::class,
        ];
    }
}
