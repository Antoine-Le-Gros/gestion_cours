<?php

namespace App\DataFixtures;

use App\Factory\TagFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tagNames = ['Développement back', 'Développement front', 'Réseaux', 'Base de données', 'Communication', 'Collaboration'];
        foreach ($tagNames as $tagName) {
            TagFactory::createOne(['name' => $tagName]);
        }

        $manager->flush();
    }
}
