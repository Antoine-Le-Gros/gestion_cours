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
            ->andWhere('s.number = :semester')
            ->andWhere('y.id = (SELECT MAX(y2.id) FROM App\Entity\Year y2)')
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
            ->andWhere('y.id = (SELECT MAX(y2.id) FROM App\Entity\Year y2)')
            ->andWhere('s.number = :semester')
            ->setParameter('search', '%'.$search.'%')
            ->setParameter('semester', $semester)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return CourseTitle[]
     */
    public function findBySemesterId(int $semesterId): array
    {
        return $this->createQueryBuilder('ct')
            ->select('c', 'ct', 'hv', 'm', 's', 'a', 'tc', 't')
            ->join('ct.modules', 'm')
            ->join('m.semester', 's')
            ->join('ct.courses', 'c')
            ->join('c.hourlyVolumes', 'hv')
            ->join('c.typeCourse', 'tc')
            ->leftJoin('c.affectations', 'a')
            ->leftJoin('a.teacher', 't')
            ->where('s.id = :semesterId')
            ->setParameter('semesterId', $semesterId)
            ->getQuery()
            ->getResult();
    }
}
