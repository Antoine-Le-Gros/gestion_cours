<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Affectation;
use App\Repository\AffectationRepository;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @template T
 *
 * @implements ProviderInterface<Affectation>
 */
class SemesterAffectationProvider implements ProviderInterface
{
    private AffectationRepository $repository;
    private Security $security;

    public function __construct(AffectationRepository $repository, Security $security)
    {
        $this->repository = $repository;
        $this->security = $security;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $semesterId = (int) $uriVariables['id'];

        // Check if the user has the required role
        if (!$this->security->isGranted('ROLE_USER')) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        return $this->repository->findAffectationBySemester($semesterId);
    }
}
