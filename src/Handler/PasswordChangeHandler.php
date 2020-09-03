<?php

namespace App\Handler;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PasswordChangeHandler extends AbstractHandler
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        EntityManagerInterface $entityManager, 
        UserPasswordEncoderInterface $encoder, 
        FlashBagInterface $flashBag,
        TranslatorInterface $translator
    ) {
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
        $this->flashBag = $flashBag;
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    function getFormType(): string
    {
        return PasswordUserType::class;
    }

    /**
     * @inheritDoc
     */
    function process($data): void
    {
        $user = $data;
        $passwordData = $this->getRequest()->request->get("password_user");

        if($this->encoder->isPasswordValid($user, $passwordData["password"]) && ($passwordData["newPassword"]["first"] === $passwordData["newPassword"]["second"])) {
            $user->setPassword($this->encoder->encodePassword($user, $passwordData["newPassword"]["first"]));

            $this->entityManager->flush();
        } else {
            $this->flashBag->add("danger", $this->translator->trans("alert.profile.danger.passwordReset", [], "alert"));
        }
    }

    /**
     * @inheritDoc
     */
    function remove($data): void 
    {}
}