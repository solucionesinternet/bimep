<?php

namespace App\Repository;

use App\Entity\InvertersQueries;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InvertersQueries|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvertersQueries|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvertersQueries[]    findAll()
 * @method InvertersQueries[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvertersQueriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvertersQueries::class);
    }

    // /**
    //  * @return InvertersQueries[] Returns an array of InvertersQueries objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InvertersQueries
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
