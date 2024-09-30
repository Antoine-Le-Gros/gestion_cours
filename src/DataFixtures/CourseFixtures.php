<?php

namespace App\DataFixtures;

use App\Factory\CourseFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CourseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        CourseFactory::createMany(50);

        $manager->flush();
    }
}
