<?php

namespace App\Repository;

use App\Entity\Turbines;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Turbines|null find($id, $lockMode = null, $lockVersion = null)
 * @method Turbines|null findOneBy(array $criteria, array $orderBy = null)
 * @method Turbines[]    findAll()
 * @method Turbines[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TurbinesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Turbines::class);
    }

    // /**
    //  * @return Turbines[] Returns an array of Turbines objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Turbines
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
