<?php

namespace App\DataFixtures;

use App\Factory\SemesterFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SemesterFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        SemesterFactory::createMany(20);

        $manager->flush();
    }
}
