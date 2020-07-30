<?php

namespace App\Repository;

use App\Entity\InternalTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InternalTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method InternalTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method InternalTemplate[]    findAll()
 * @method InternalTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InternalTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InternalTemplate::class);
    }

    // /**
    //  * @return InternalTemplate[] Returns an array of InternalTemplate objects
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
    public function findOneBySomeField($value): ?InternalTemplate
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
