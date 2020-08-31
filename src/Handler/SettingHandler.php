<?php

namespace App\Handler;

use App\Form\LocaleType;
use Doctrine\ORM\EntityManagerInterface;

class SettingHandler extends AbstractHandler
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
        return LocaleType::class;
    }

    /**
     * @inheritDoc
     */
    function process($data): void
    {
        $this->entityManager->flush();
    }
}