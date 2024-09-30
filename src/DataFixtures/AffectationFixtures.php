<?php

namespace App\DataFixtures;

use App\Factory\AffectationFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AffectationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        AffectationFactory::createMany(10);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CourseFixtures::class,
            UserFixtures::class,
        ];
    }
}
