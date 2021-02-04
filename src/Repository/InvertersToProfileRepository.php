<?php

namespace App\Repository;

use App\Entity\InvertersToProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InvertersToProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvertersToProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvertersToProfile[]    findAll()
 * @method InvertersToProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvertersToProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvertersToProfile::class);
    }

    // /**
    //  * @return InvertersToProfile[] Returns an array of InvertersToProfile objects
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
    public function findOneBySomeField($value): ?InvertersToProfile
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
