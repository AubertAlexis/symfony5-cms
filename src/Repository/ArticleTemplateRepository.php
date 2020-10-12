<?php

namespace App\Repository;

use App\Entity\ArticleTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArticleTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleTemplate[]    findAll()
 * @method ArticleTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleTemplate::class);
    }

    /**
     * @return ArticleTemplate[]
     */
    public function findByArticlesEnabled()
    {
        return $this->createQueryBuilder('a')
            ->join("a.page", "p")
            ->andWhere('p.enabled = :val')
            ->setParameter('val', true)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?ArticleTemplate
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
