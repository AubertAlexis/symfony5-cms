<?php

namespace App\Handler;

use App\Form\SubNavType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;

class SubNavHandler extends AbstractHandler
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
        return SubNavType::class;
    }

    /**
     * @inheritDoc
     */
    function process($data): void
    {

        if($this->entityManager->getUnitOfWork()->getEntityState($data) == UnitOfWork::STATE_NEW) {
            $data->setParent($this->getRequest()->attributes->get("navLink"));
            $this->entityManager->persist($data);
        } 

        /**
         * @var NavLink $navLinkChildren
         */
        foreach ($data->getNavLinks() as $navLinkChildren) {
            $data->addNavlink($navLinkChildren);
            $navLinkChildren->setSubNav($data);
            $this->entityManager->persist($navLinkChildren);
        }

        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    function remove($data): void 
    {}
}