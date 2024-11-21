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

    /**
     * @return CourseTitle[]
     */
    public function findForSemesterFromCurrentYearAndTag(string $search, int $tag, int $semester): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.tags', 't')
            ->join('c.modules', 'm')
            ->join('m.semester', 's')
            ->join('s.year', 'y')
            ->andWhere('UPPER(c.name) LIKE UPPER(:search)')
            ->andWhere('t.id = :tag')
            ->andWhere('y.isCurrent = TRUE ')
            ->andWhere('s.number = :semester')
            ->setParameter('search', '%'.$search.'%')
            ->setParameter('tag', $tag)
            ->setParameter('semester', $semester)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return CourseTitle[]
     */
    public function findForSemesterFromCurrentYear(string $search, int $semester): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.modules', 'm')
            ->join('m.semester', 's')
            ->join('s.year', 'y')
            ->andWhere('UPPER(c.name) LIKE UPPER(:search)')
            ->andWhere('y.isCurrent = TRUE')
            ->andWhere('s.number = :semester')
            ->setParameter('search', '%'.$search.'%')
            ->setParameter('semester', $semester)
            ->getQuery()
            ->getResult();
    }
}
