<?php

namespace App\Repository;

use App\Entity\BuoysFilesUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BuoysFilesUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuoysFilesUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuoysFilesUsers[]    findAll()
 * @method BuoysFilesUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuoysFilesUsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BuoysFilesUsers::class);
    }

    // /**
    //  * @return BuoysFilesUsers[] Returns an array of BuoysFilesUsers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BuoysFilesUsers
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
