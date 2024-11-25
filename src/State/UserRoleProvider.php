<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\User;

/**
 * @implements ProviderInterface<User>
 */
class UserRoleProvider implements ProviderInterface
{
    public function __construct()
    {
    }

    /**
     * @return array<int,string>|object|object[]|null
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|object|null
    {
        return User::TYPE_TEACHER;
    }
}
