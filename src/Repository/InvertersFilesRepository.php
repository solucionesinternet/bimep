<?php

namespace App\Repository;

use App\Entity\InvertersFiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InvertersFiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvertersFiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvertersFiles[]    findAll()
 * @method InvertersFiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvertersFilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvertersFiles::class);
    }

    // /**
    //  * @return InvertersFiles[] Returns an array of InvertersFiles objects
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
    public function findOneBySomeField($value): ?InvertersFiles
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
