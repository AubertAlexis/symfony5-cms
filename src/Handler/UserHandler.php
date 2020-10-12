<?php

namespace App\Handler;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserHandler extends AbstractHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
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
        if($this->entityManager->getUnitOfWork()->getEntityState($data) == UnitOfWork::STATE_NEW) {
            $encodedPassword = $this->userPasswordEncoder->encodePassword($data, $data->getPassword());
            $data->setPassword($encodedPassword);
            
            $this->entityManager->persist($data);
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