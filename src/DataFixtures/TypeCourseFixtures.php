<?php

namespace App\DataFixtures;

use App\Factory\TypeCourseFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TypeCourseFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $typesCourse = ['CM', 'TD', 'TP', 'TDM'];
        foreach ($typesCourse as $typeCourse) {
            TypeCourseFactory::createOne(['name' => $typeCourse]);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [
            'typeCourse',
        ];
    }
}
