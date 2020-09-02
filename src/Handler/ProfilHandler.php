<?php

namespace App\Handler;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;

class ProfilHandler extends AbstractHandler
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
        return UserType::class;
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