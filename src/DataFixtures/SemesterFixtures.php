<?php

namespace App\DataFixtures;

use App\Factory\SemesterFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SemesterFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 6; ++$i) {
            SemesterFactory::createOne(['number' => $i]);
        }
        $manager->flush();
    }
}
