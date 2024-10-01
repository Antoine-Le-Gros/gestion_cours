<?php

namespace App\Factory;

use App\Entity\Affectation;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Affectation>
 */
final class AffectationFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Affectation::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function defaults(): array|callable
    {
        $course = CourseFactory::random();

        return [
            'course' => $course,
            'teacher' => UserFactory::random(),
            'numberGroupTaken' => self::faker()->numberBetween(1, $course->getGroupMaxNumber()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Affectation $affectation): void {})
        ;
    }
}
