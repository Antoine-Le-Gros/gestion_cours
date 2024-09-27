<?php

namespace App\Factory;

use App\Entity\ExternalHourRecord;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<ExternalHourRecord>
 */
final class ExternalHourRecordFactory extends PersistentProxyObjectFactory
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
        return ExternalHourRecord::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'hours' => self::faker()->randomFloat(1, 0, 192),
            'teacher' => UserFactory::random(),
            'year' => YearFactory::random(),
        ];
    }

    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(ExternalHourRecord $externalHourRecord): void {})
        ;
    }
}
