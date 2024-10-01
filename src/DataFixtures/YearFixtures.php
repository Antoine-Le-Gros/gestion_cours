<?php

namespace App\DataFixtures;

use App\Factory\YearFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class YearFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        YearFactory::createOne();

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SemesterFixtures::class,
        ];
    }
}
