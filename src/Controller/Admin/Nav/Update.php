<?php

namespace App\Controller\Admin\Nav;

use App\Entity\Nav;
use App\Handler\NavHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Update extends AbstractController
{
    /**
     * @Route("admin/navigations/{id}", name="admin_nav_edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param Nav $nav
     * @param NavHandler $navHandler
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function update(Request $request, Nav $nav, NavHandler $navHandler, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted("NAV_EDIT");

        if ($navHandler->handle($request, $nav, ['isEnabled' => $nav->getEnabled()])) {
            $this->addFlash("success", $translator->trans("alert.nav.success.edit", [], "alert"));
            return $this->redirectToRoute("admin_nav_index");
        }

        return $this->render('admin/nav/edit.html.twig', [
            'form' => $navHandler->createView()
        ]);
    }

}