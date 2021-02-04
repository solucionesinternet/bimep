<?php

namespace App\Repository;

use App\Entity\TurbinesDatas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TurbinesDatas|null find($id, $lockMode = null, $lockVersion = null)
 * @method TurbinesDatas|null findOneBy(array $criteria, array $orderBy = null)
 * @method TurbinesDatas[]    findAll()
 * @method TurbinesDatas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TurbinesDatasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurbinesDatas::class);
    }

    // /**
    //  * @return TurbinesDatas[] Returns an array of TurbinesDatas objects
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
    public function findOneBySomeField($value): ?TurbinesDatas
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
