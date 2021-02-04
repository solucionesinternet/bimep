<?php

namespace App\Repository;

use App\Entity\TurbinesFiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TurbinesFiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method TurbinesFiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method TurbinesFiles[]    findAll()
 * @method TurbinesFiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TurbinesFilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurbinesFiles::class);
    }

    // /**
    //  * @return TurbinesFiles[] Returns an array of TurbinesFiles objects
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
    public function findOneBySomeField($value): ?TurbinesFiles
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
