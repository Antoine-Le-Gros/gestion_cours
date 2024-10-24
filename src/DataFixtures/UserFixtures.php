<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(4);
        UserFactory::createOne([
            'email' => 'agregate@example.com',
            'roles' => [User::ROLES['AGR']],
            'password' => $this->passwordHasher->hashPassword(new User(), 'test'),
            'hoursMax' => 500,
            'firstname' => 'Jean',
            'lastname' => 'Dupont',
            'login' => 'jdupont',
        ]);

        UserFactory::createOne([
            'email' => 'vacataire@example.com',
            'roles' => [User::ROLES['VAC']],
            'password' => $this->passwordHasher->hashPassword(new User(), 'test'),
            'hoursMax' => 150,
            'firstname' => 'Marie',
            'lastname' => 'Durand',
            'login' => 'mdurand',
        ]);

        UserFactory::createOne([
            'email' => 'chercheur@example.com',
            'roles' => [User::ROLES['CHE']],
            'password' => $this->passwordHasher->hashPassword(new User(), 'test'),
            'hoursMax' => 250,
            'firstname' => 'Pierre',
            'lastname' => 'Martin',
            'login' => 'pmartin',
        ]);

        UserFactory::createOne([
            'email' => 'admin@example.com',
            'roles' => [User::ROLES['AD']],
            'password' => $this->passwordHasher->hashPassword(new User(), 'test'),
            'hoursMax' => 0,
            'firstname' => 'Pierre',
            'lastname' => 'Martin',
            'login' => 'admin',
        ]);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [
            'user',
        ];
    }
}
