<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Course>
 *
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function save(Course $course, bool $flush = false): void
    {
        $this->getEntityManager()->persist($course);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Course $course, bool $flush = false): void
    {
        $this->getEntityManager()->remove($course);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Add custom query methods if needed
}
