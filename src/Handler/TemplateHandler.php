<?php

namespace App\Handler;

use App\Form\TemplateType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;

class TemplateHandler extends AbstractHandler
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
        return TemplateType::class;
    }

    /**
     * @inheritDoc
     */
    function process($data): void
    {
        if($this->entityManager->getUnitOfWork()->getEntityState($data) == UnitOfWork::STATE_NEW) {
            $this->entityManager->persist($data);
        }

        $this->entityManager->flush();
    }
}