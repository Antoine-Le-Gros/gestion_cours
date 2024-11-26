<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Course>
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function getNumberOfGroupsPerSemester(int $semesterId): int
    {
        return $this->createQueryBuilder('c')
            ->select('SUM(c.groupMaxNumber) as nbGroups')
            ->join('c.courseTitle', 'ct')
            ->join('ct.modules', 'm')
            ->join('m.semester', 's')
            ->where('s.id = :semesterId')
            ->setParameter('semesterId', $semesterId)
            ->getQuery()
            ->getResult()[0]['nbGroups'];
    }

    /**
     * @return Course[]
     */
    public function findBySearchQuery(string $query, ?string $type = null): array
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.courseTitle', 'ct')
            ->join('c.typeCourse', 't');

        $qb->orWhere('LOWER(ct.name) LIKE LOWER(:query)')
            ->setParameter('query', '%'.$query.'%');

        if ($type) {
            $qb->andWhere('t.name LIKE :type')
                ->setParameter('type', $type);
        }

        return $qb->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Course[] Returns an array of Course objects
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

    //    public function findOneBySomeField($value): ?Course
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
