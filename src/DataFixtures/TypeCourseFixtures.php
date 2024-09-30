<?php

namespace App\DataFixtures;

use App\Factory\TypeCourseFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeCourseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TypeCourseFactory::createMany(4);

        $manager->flush();
    }
}
