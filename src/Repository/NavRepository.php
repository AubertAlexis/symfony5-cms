<?php

namespace App\Repository;

use App\Entity\Nav;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Nav|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nav|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nav[]    findAll()
 * @method Nav[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NavRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nav::class);
    }

    // /**
    //  * @return Nav[] Returns an array of Nav objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Nav
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
