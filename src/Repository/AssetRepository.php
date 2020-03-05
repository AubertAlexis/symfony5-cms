<?php

namespace App\Repository;

use App\Entity\Asset;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Asset|null find($id, $lockMode = null, $lockVersion = null)
 * @method Asset|null findOneBy(array $criteria, array $orderBy = null)
 * @method Asset[]    findAll()
 * @method Asset[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asset::class);
    }

    public function findAssetToRemove(array $filenames, int $pageId)
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->select()
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('a.page', $pageId),
                    $qb->expr()->notIn('a.fileName', $filenames)
                )
            );

        return $qb->getQuery()->getResult();
    }
}
