<?php

namespace App\Handler;

use App\Form\ContactType;
use App\Services\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;

class ContactHandler extends AbstractHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    /**
     * @var Mailer
     */
    private $mailerHelper;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, Mailer $mailerHelper, Swift_Mailer $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailerHelper = $mailerHelper;
        $this->mailer = $mailer;
    }

    /**
     * @inheritDoc
     */
    function getFormType(): string
    {
        return ContactType::class;
    }

    /**
     * @inheritDoc
     */
    function process($data): void
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        $email = $this->mailerHelper->makeMessage(
            "Nouveau message de la part d'un utilisateur",
            $data->getEmail(),
            "emails/_contact.html.twig",
            [
                "date" => new \DateTime(),
                "contact" => $data
            ]
        );
    
        $this->mailer->send($email);

    }

    /**
     * @inheritDoc
     */
    function remove($data): void 
    {}
}