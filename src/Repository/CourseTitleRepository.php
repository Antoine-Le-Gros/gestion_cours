<?php

namespace App\Repository;

use App\Entity\CourseTitle;
use App\Entity\Module;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CourseTitle>
 */
class CourseTitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseTitle::class);
    }

    /**
     * @param Module[] $modules
     */
    public function findOneByNameAndModules(string $name, array $modules): ?CourseTitle
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.modules', 'm')
            ->andWhere('c.name = :name')
            ->andWhere('m.id IN (:modules)')
            ->setParameter('name', $name)
            ->setParameter('modules', $modules)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return CourseTitle[] Returns an array of CourseTitle objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CourseTitle
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
