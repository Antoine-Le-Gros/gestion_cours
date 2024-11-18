<?php

namespace App\Factory;

use App\Entity\Year;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Year>
 */
final class YearFactory extends PersistentProxyObjectFactory
{
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Year::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'name' => '2024/2025',
            'isCurrent' => false,
        ];
    }

    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Year $year): void {})
        ;
    }
}
