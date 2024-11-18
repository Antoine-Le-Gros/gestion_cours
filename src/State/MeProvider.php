<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @implements ProviderInterface<UserInterface>
 */
class MeProvider implements ProviderInterface
{
    private Security $security;

    /**
     * @return UserInterface|array|object[]|null
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): UserInterface|array|null
    {
        return $this->security->getUser();
    }

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
}
