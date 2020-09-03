<?php

namespace App\Handler;

use App\Entity\NavLink;
use App\Form\NavType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;

class NavHandler extends AbstractHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    function getFormType(): string
    {
        return NavType::class;
    }

    /**
     * @inheritDoc
     */
    function process($data): void
    {
        if($this->entityManager->getUnitOfWork()->getEntityState($data) == UnitOfWork::STATE_NEW) {
            $this->entityManager->persist($data);
        } 

        /**
         * @var NavLink $navLink
         */
        foreach ($data->getNavLinks() as $navLink) {
            $navLink->setNav($data);

            $this->entityManager->persist($navLink);
        }

        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    function remove($data): void 
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}