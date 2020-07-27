<?php

namespace App\Repository;

use App\Entity\SubNav;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubNav|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubNav|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubNav[]    findAll()
 * @method SubNav[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubNavRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubNav::class);
    }

    // /**
    //  * @return SubNav[] Returns an array of SubNav objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubNav
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
