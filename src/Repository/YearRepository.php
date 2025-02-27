<?php

namespace App\Repository;

use App\Entity\Year;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Year>
 */
class YearRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Year::class);
    }

    public function findById(int $id): Year
    {
        return $this->createQueryBuilder('y')
            ->where('y.id = :id')
            ->setParameter('id', $id)
            ->getQuery()->getResult()[0];
    }

    /**
     * @return Year[]
     */
    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('y')
            ->where('y.name LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->orderBy('y.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findCurrent(): Year
    {
        return $this->createQueryBuilder('y')
            ->where('y.isCurrent = TRUE')
            ->getQuery()->getResult()[0];
    }
    //    /**
    //     * @return Year[] Returns an array of Year objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('y')
    //            ->andWhere('y.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('y.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Year
    //    {
    //        return $this->createQueryBuilder('y')
    //            ->andWhere('y.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
