<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/admin")
 */
class AdminAuthController extends AbstractController
{

    /**
     * @Route("/connexion", name="admin_login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils): Response
    {
        return $this->render('admin/auth/login.html.twig', [
            'error' => $utils->getLastAuthenticationError() !== null,
            'username' => $utils->getLastUsername()
        ]);
    }

    /**
     * @Route("", name="admin_login_base")
     * @return RedirectResponse
     */
    public function redirectToAdminLogin(): RedirectResponse
    {
        return $this->redirectToRoute("admin_login", [], Response::HTTP_MOVED_PERMANENTLY);
    }

    /**
     * @Route("/deconnexion", name="logout")
     */
    public function logout(){}

}