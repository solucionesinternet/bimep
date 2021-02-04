<?php

namespace App\Repository;

use App\Entity\InvertersHistoricSearches;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InvertersHistoricSearches|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvertersHistoricSearches|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvertersHistoricSearches[]    findAll()
 * @method InvertersHistoricSearches[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvertersHistoricSearchesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvertersHistoricSearches::class);
    }

    // /**
    //  * @return InvertersHistoricSearches[] Returns an array of InvertersHistoricSearches objects
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
    public function findOneBySomeField($value): ?InvertersHistoricSearches
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
