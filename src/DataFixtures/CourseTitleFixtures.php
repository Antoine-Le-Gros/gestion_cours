<?php

namespace App\DataFixtures;

use App\Factory\CourseTitleFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CourseTitleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        CourseTitleFactory::createMany(20);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ModuleFixtures::class,
        ];
    }
}
