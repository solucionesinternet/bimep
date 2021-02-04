<?php

namespace App\Repository;

use App\Entity\InvertersDatas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InvertersDatas|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvertersDatas|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvertersDatas[]    findAll()
 * @method InvertersDatas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvertersDatasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvertersDatas::class);
    }

    // /**
    //  * @return InvertersDatas[] Returns an array of InvertersDatas objects
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
    public function findOneBySomeField($value): ?InvertersDatas
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
