<?php

namespace App\Repository;

use App\Entity\VideosAlbum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method VideosAlbum|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideosAlbum|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideosAlbum[]    findAll()
 * @method VideosAlbum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideosAlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideosAlbum::class);
    }

    // /**
    //  * @return VideosAlbum[] Returns an array of VideosAlbum objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VideosAlbum
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
