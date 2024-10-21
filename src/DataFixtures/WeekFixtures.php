<?php

namespace App\DataFixtures;

use App\Factory\WeekFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WeekFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 53; ++$i) {
            WeekFactory::createOne(['number' => $i]);
        }

        $manager->flush();
    }
}
