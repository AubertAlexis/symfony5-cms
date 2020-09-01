<?php

namespace App\Handler;

use App\Form\NavType;
use Doctrine\ORM\EntityManagerInterface;

class NavLinkHandler extends AbstractHandler
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
    {}

    /**
     * @inheritDoc
     */
    function remove($data): void 
    {
        if ($data->getSubNav() && $data->getSubNav()->getNavlinks()->count() == 1) {
            $this->entityManager->remove($data->getSubNav());
        }

        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}