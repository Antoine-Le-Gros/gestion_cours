<?php

namespace App\Repository;

use App\Entity\Affectation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Affectation>
 */
class AffectationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Affectation::class);
    }

    /**
     * @return Affectation[]
     */
    public function findAffectationByUserAndYear(int $userId, int $yearId): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.teacher', 't')
            ->join('a.course', 'c')
            ->join('c.hourlyVolumes', 'h')
            ->join('h.week', 'w')
            ->join('w.semesters', 's')
            ->join('s.year', 'y')
            ->andWhere('t.id = :userId')
            ->andWhere('y.id = :yearId')
            ->setParameter('userId', $userId)
            ->setParameter('yearId', $yearId)
            ->orderBy('s.number', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getAllAffectationGroupsTakenBySemester(int $semesterId): float
    {
        return $this->createQueryBuilder('a')
            ->select('SUM(a.numberGroupTaken) as nbGroups')
            ->join('a.course', 'c')
            ->join('c.courseTitle', 'ct')
            ->join('ct.modules', 'm')
            ->join('m.semester', 's')
            ->where('s.id = :semesterId')
            ->setParameter('semesterId', $semesterId)
            ->getQuery()
            ->getResult()[0]['nbGroups'];
    }

    //    /**
    //     * @return Affectation[] Returns an array of Affectation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Affectation
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
