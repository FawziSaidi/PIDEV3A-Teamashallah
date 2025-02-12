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

    public function countUsersCreatedLast24Hours(): int
    {
        $yesterday = new \DateTime('-24 hours');

        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.created_at >= :yesterday')
            ->setParameter('yesterday', $yesterday)
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    public function getUserIncreaseLastWeek(): array
    {
        $oneWeekAgo = new \DateTime('-1 week');
        $now = new \DateTime();

        $qb = $this->createQueryBuilder('u');

        $totalUsers = $qb
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $newUsers = $qb
            ->select('COUNT(u.id)')
            ->where('u.created_at BETWEEN :oneWeekAgo AND :now')
            ->setParameter('oneWeekAgo', $oneWeekAgo)
            ->setParameter('now', $now)
            ->getQuery()
            ->getSingleScalarResult();

        $increasePercentage = ($totalUsers > 0) ? ($newUsers / $totalUsers) * 100 : 0;

        return [
            'totalUsers' => $totalUsers,
            'newUsers' => $newUsers,
            'increasePercentage' => round($increasePercentage, 2)
        ];
    }
    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
