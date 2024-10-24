<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{
    private UserPasswordHasherInterface $passwordHasher;
    private \Transliterator $transliterator;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->transliterator = \Transliterator::create('Any-Latin; Latin-ASCII');
        $this->passwordHasher = $passwordHasher;
    }

    public static function class(): string
    {
        return User::class;
    }

    protected function defaults(): array|callable
    {
        $firstName = $this->normalizeName(self::faker()->firstName());
        $lastName = $this->normalizeName(self::faker()->lastName());
        $numerified = self::faker()->unique()->numerify('###');
        $email = "user-$numerified@example.com";
        $role = array_rand(array_flip(User::ROLES));
        $hoursMax = $this->getRandomHoursMaxForRole($role);

        return [
            'email' => $email,
            'firstname' => $firstName,
            'isActive' => true,
            'lastname' => $lastName,
            'login' => "test-$numerified",
            'password' => $this->passwordHasher->hashPassword(new User(), 'test'),
            'roles' => [$role],
            'hoursMax' => $hoursMax,
        ];
    }

    protected function getRandomHoursMaxForRole(string $role): int
    {
        switch ($role) {
            case User::ROLES['AGR']:
            case User::ROLES['CER']:
                return self::faker()->numberBetween(384, 768);
            case User::ROLES['CHE']:
                return self::faker()->numberBetween(192, 384);
            case User::ROLES['VAC']:
                return self::faker()->numberBetween(0, 192);
            default:
                return 0;
        }
    }

    protected function normalizeName(string $name): string
    {
        $name = preg_replace('/[^\p{L}0-9]+/u', '-', $name);
        $name = $this->transliterator->transliterate($name);

        return mb_strtolower($name);
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(User $user): void {})
        ;
    }
}
