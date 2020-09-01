<?php

namespace App\Handler;

use App\Form\HomePageType;
use Doctrine\ORM\EntityManagerInterface;

class HomeHandler extends AbstractHandler
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
        return HomePageType::class;
    }

    /**
     * @inheritDoc
     */
    function process($data): void
    {
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    function remove($data): void 
    {}
}