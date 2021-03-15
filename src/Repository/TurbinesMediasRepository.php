<?php

namespace App\Repository;

use App\Entity\TurbinesMedias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TurbinesMedias|null find($id, $lockMode = null, $lockVersion = null)
 * @method TurbinesMedias|null findOneBy(array $criteria, array $orderBy = null)
 * @method TurbinesMedias[]    findAll()
 * @method TurbinesMedias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TurbinesMediasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurbinesMedias::class);
    }

    // /**
    //  * @return TurbinesMedias[] Returns an array of TurbinesMedias objects
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
    public function findOneBySomeField($value): ?TurbinesMedias
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
