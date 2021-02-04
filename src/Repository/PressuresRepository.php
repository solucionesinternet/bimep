<?php

namespace App\Repository;

use App\Entity\Pressures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pressures|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pressures|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pressures[]    findAll()
 * @method Pressures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PressuresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pressures::class);
    }

    // /**
    //  * @return Pressures[] Returns an array of Pressures objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pressures
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
