<?php

namespace App\Controller\Front\Rgpd;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DeleteUser extends AbstractController
{
    /**
     * @Route("/suppression/compte", name="rgpd_delete_user")
     *
     * @param EntityManagerInterface $entityManagerInterface
     * @param TokenStorageInterface $tokenStorageInterface
     * @return Response
     */
    public function deleteUser(EntityManagerInterface $entityManagerInterface, TokenStorageInterface $tokenStorageInterface): Response
    {
        $entityManagerInterface->remove($this->getUser());
        $entityManagerInterface->flush();
        $tokenStorageInterface->setToken(null);

        $this->addFlash("success", "Votre compte a été supprimé avec succès.");
        return $this->redirectToRoute("home_index");
    }
}
