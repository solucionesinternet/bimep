<?php

namespace App\Repository;

use App\Entity\BuoysFiles;
use App\Entity\BuoysFilesUsers;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BuoysFiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuoysFiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuoysFiles[]    findAll()
 * @method BuoysFiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuoysFilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BuoysFiles::class);
    }

    // /**
    //  * @return BuoysFiles[] Returns an array of BuoysFiles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BuoysFiles
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    //  * @Devuelve un Array con los resultados de los posts existentes
    //  */
    public function listFiles($user){

        /*  La diferencia entre uno y otro es pasar o bien los posts o bien la consulta
        return $this->getEntityManager()->createQuery('SELECT posts.id, posts.titulo, posts.foto, posts.fecha_publicacion
            FROM App:Posts posts')->getResult();
        */
//        return $this->getEntityManager()->createQuery('SELECT f.id, fc.category,  f.date_start, f.date_end, f.downloads, f.filename, fc.category
//            FROM App\Entity\BuoysFiles f, App\Entity\FilesCategories fc WHERE f.files_categories = fc.id');
//
        return $this->createQueryBuilder('BuoysFiles')
            ->select('BuoysFiles.id, BuoysFiles.filename, BuoysFiles.date_start, BuoysFiles.date_end, files_categories.category')
            ->leftJoin('BuoysFiles.files_categories', 'files_categories')
            ->addSelect('(SELECT bfu.downloads FROM App\Entity\BuoysFilesUsers bfu WHERE bfu.buoys_files = BuoysFiles.id AND bfu.user = :user_id) AS numDownloads')
           ->setParameter('user_id', $user)
            ->getQuery();



    }

}
