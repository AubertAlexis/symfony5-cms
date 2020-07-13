<?php

namespace App\Repository;

use App\Entity\NavLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NavLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method NavLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method NavLink[]    findAll()
 * @method NavLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NavLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NavLink::class);
    }

    // /**
    //  * @return NavLink[] Returns an array of NavLink objects
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
    public function findOneBySomeField($value): ?NavLink
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
