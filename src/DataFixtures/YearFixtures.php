<?php

namespace App\DataFixtures;

use App\Factory\YearFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class YearFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        YearFactory::createOne();

        $manager->flush();
    }
}
