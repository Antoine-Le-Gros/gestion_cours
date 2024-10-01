<?php

namespace App\DataFixtures;

use App\Factory\HourlyVolumeFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class HourlyVolumeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        HourlyVolumeFactory::createMany(50);
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
