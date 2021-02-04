<?php

namespace App\Repository;

use App\Entity\Queries;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Queries|null find($id, $lockMode = null, $lockVersion = null)
 * @method Queries|null findOneBy(array $criteria, array $orderBy = null)
 * @method Queries[]    findAll()
 * @method Queries[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QueriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Queries::class);
    }

    // /**
    //  * @return Queries[] Returns an array of Queries objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Queries
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
