<?php

namespace App\Controller\Admin\Nav;

use App\Entity\Nav;
use App\Handler\NavHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class Delete extends AbstractController
{
    /**
    * @Route("admin/navigations/{id}/suppression", name="admin_nav_delete", requirements={"id": "\d+"}, methods="POST")
    * @param Request $request
    * @param Nav $nav
    * @param NavHandler $navHandler
    * @param CsrfTokenManagerInterface $tokenManager
    * @param TranslatorInterface $translator
    * @return Response
    */
    public function delete(
        Request $request, 
        Nav $nav, 
        NavHandler $navHandler, 
        CsrfTokenManagerInterface $tokenManager,
        TranslatorInterface $translator
    ) : Response
    {
        $this->denyAccessUnlessGranted("NAV_DELETE");

        if ($navHandler->validateToken($tokenManager, "delete-nav", $request->request->get('token'), $nav)) {
            $this->addFlash("success", $translator->trans("alert.nav.success.delete", [], "alert"));
            return $this->redirectToRoute("admin_nav_index");
        }

        $this->addFlash("danger", $translator->trans("error.invalidCsrf", [], "error"));

        return $this->redirectToRoute("admin_nav_index");
    }
}