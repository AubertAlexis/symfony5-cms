<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAuthController extends AbstractController
{
    /**
     * @Route("/admin/connexion", name="admin_login")
     *
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils) : Response
    {
           return $this->render('admin/auth/login.html.twig', [
                'error' => $utils->getLastAuthenticationError() !== null,
                'username' => $utils->getLastUsername()
           ]);
    }

    /**
     * @Route("/deconnexion", name="logout")
     */
    public function logout(){}

}