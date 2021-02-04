<?php

namespace App\Repository;

use App\Entity\FilesCategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FilesCategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method FilesCategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method FilesCategories[]    findAll()
 * @method FilesCategories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilesCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FilesCategories::class);
    }

    // /**
    //  * @return FilesCategories[] Returns an array of FilesCategories objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FilesCategories
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
