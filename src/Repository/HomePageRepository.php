<?php

namespace App\Repository;

use App\Entity\HomePage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HomePage|null find($id, $lockMode = null, $lockVersion = null)
 * @method HomePage|null findOneBy(array $criteria, array $orderBy = null)
 * @method HomePage[]    findAll()
 * @method HomePage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HomePageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HomePage::class);
    }

    // /**
    //  * @return HomePage[] Returns an array of HomePage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HomePage
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
