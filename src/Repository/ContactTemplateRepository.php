<?php

namespace App\Repository;

use App\Entity\ContactTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ContactTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactTemplate[]    findAll()
 * @method ContactTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactTemplateTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactTemplate::class);
    }
    
    /*
    public function findOneBySomeField($value): ?ContactTemplate
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
