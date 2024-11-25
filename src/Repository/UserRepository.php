<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findOneByLoginOrEmail(string $identifier): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.login = :identifier OR u.email = :identifier')
            ->setParameter('identifier', $identifier)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return User[]
     */
    public function findBySearchQuery(string $query, ?bool $isActive = null, ?string $role = null): array
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where('u.lastname LIKE :query')
            ->orWhere('u.firstname LIKE :query')
            ->orWhere('u.email LIKE :query')
            ->orWhere('u.login LIKE :query')
            ->setParameter('query', '%'.$query.'%');

        if (null !== $isActive) {
            $qb->andWhere('u.isActive = :isActive')
                ->setParameter('isActive', $isActive);
        }

        if ($role) {
            $qb->andWhere('CONTAINS(TO_JSONB(u.roles), :role) = TRUE')
                ->setParameter('role', json_encode([$role]));
        }

        return $qb->orderBy('u.lastname', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return User[]
     */
    public function findBySearchAndRole(string $search, ?string $role): array
    {
        $query = $this->createQueryBuilder('u')
            ->where('UPPER(u.lastname) LIKE UPPER(:search)')
            ->orWhere('UPPER(u.firstname) LIKE UPPER(:search)')
            ->setParameter('search', '%'.$search.'%');

        if ($role) {
            $query->andWhere('CONTAINS(TO_JSONB(u.roles), :role) = TRUE')
                ->setParameter('role', json_encode([$role]));
        } else {
            //            $query->orWhere('CONTAINS(TO_JSONB(u.roles), :role1) = TRUE')
            //                ->orWhere('CONTAINS(TO_JSONB(u.roles), :role2) = TRUE')
            //                ->orWhere('CONTAINS(TO_JSONB(u.roles), :role3) = TRUE')
            //                ->orWhere('CONTAINS(TO_JSONB(u.roles), :role4) = TRUE')
            $query->andWhere('NOT CONTAINS(TO_JSONB(u.roles), :role) = TRUE')
            //                ->setParameter('role1', json_encode(User::AGGREGATED))
            //                ->setParameter('role2', json_encode(User::EXTERNAL))
            //                ->setParameter('role3', json_encode(User::CERTIFIED))
            //                ->setParameter('role4', json_encode(User::RESEARCHER))
            ->setParameter('role', json_encode(User::SUPER_ADMINISTRATION));
        }

        return $query->getQuery()
        ->getResult();
    }
}
