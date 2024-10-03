<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(10);
        UserFactory::createOne([
            'email' => 'agregate@example.com',
            'roles' => ['ENSEIGNANT_AGRÉGÉ'],
            'hoursMax' => 500,
            'firstname' => 'Jean',
            'lastname' => 'Dupont',
            'login' => 'jdupont',
            'password' => 'test',
        ]);

        UserFactory::createOne([
            'email' => 'vacataire@example.com',
            'roles' => ['VACATAIRE'],
            'hoursMax' => 150,
            'firstname' => 'Marie',
            'lastname' => 'Durand',
            'login' => 'mdurand',
            'password' => 'test',
        ]);

        UserFactory::createOne([
            'email' => 'chercheur@example.com',
            'roles' => ['ENSEIGNANT_CHERCHEUR'],
            'hoursMax' => 250,
            'firstname' => 'Pierre',
            'lastname' => 'Martin',
            'login' => 'pmartin',
            'password' => 'test',
        ]);

        UserFactory::createOne([
            'email' => 'admin@example.com',
            'roles' => ['ADMINISTRATION'],
            'hoursMax' => 0,
            'firstname' => 'Pierre',
            'lastname' => 'Martin',
            'login' => 'admin',
            'password' => 'test',
        ]);
        $manager->flush();
    }
}
