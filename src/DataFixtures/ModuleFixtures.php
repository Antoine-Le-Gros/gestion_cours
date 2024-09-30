<?php

namespace App\DataFixtures;

use App\Factory\ModuleFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ModuleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        ModuleFactory::createMany(20);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SemesterFixtures::class,
        ];
    }
}
