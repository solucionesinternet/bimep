<?php

namespace App\Repository;

use App\Entity\Buoys;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Buoys|null find($id, $lockMode = null, $lockVersion = null)
 * @method Buoys|null findOneBy(array $criteria, array $orderBy = null)
 * @method Buoys[]    findAll()
 * @method Buoys[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuoysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Buoys::class);
    }

    // /**
    //  * @return Buoys[] Returns an array of Buoys objects
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
    public function findOneBySomeField($value): ?Buoys
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
