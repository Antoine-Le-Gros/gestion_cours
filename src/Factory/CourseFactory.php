<?php

namespace App\Factory;

use App\Entity\Course;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Course>
 */
final class CourseFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Course::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $gpMax = self::faker()->numberBetween(1, 5);
        $Sae = null;
        if (rand(0, 10) < 2) {
            $Sae = self::faker()->text(30);
        }

        return [
            'groupMaxNumber' => $gpMax,
            'SAESupport' => $Sae,
            'courseTitle' => CourseTitleFactory::random(),
            'typeCourse' => TypeCourseFactory::random(),
            'year' => YearFactory::random(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Course $course): void {})
        ;
    }
}
