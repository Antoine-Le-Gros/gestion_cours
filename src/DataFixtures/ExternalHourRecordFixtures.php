<?php

namespace App\DataFixtures;

use App\Factory\ExternalHourRecordFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ExternalHourRecordFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        ExternalHourRecordFactory::createMany(5);
        $manager->flush();
    }
}
