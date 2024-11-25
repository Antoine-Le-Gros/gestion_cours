<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @implements ProviderInterface<User>
 */
class HistoryUserProvider implements ProviderInterface
{
    private UserRepository $repository;
    private RequestStack $requestStack;

    public function __construct(UserRepository $repository, RequestStack $requestStack)
    {
        $this->repository = $repository;
        $this->requestStack = $requestStack;
    }

    /**
     * @return User[]|object|object[]|null
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|object|null
    {
        $request = $this->requestStack->getCurrentRequest();

        $search = $request->query->get('search') ?? '';
        $role = $request->query->get('role');

        return $this->repository->findBySearchAndRole($search, $role);
    }
}
