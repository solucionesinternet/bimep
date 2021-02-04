<?php

namespace App\Repository;

use App\Entity\Inverters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Inverters|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inverters|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inverters[]    findAll()
 * @method Inverters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvertersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inverters::class);
    }

    // /**
    //  * @return Inverters[] Returns an array of Inverters objects
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
    public function findOneBySomeField($value): ?Inverters
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
