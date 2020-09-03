<?php

namespace App\Controller\Admin\Nav;

use App\Entity\Nav;
use App\Handler\NavHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Create extends AbstractController
{
    /**
     * @Route("admin/navigations/nouveau", name="admin_nav_add")
     * @param Request $request
     * @param NavHandler $navHandler
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function create(Request $request, NavHandler $navHandler, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted("NAV_ADD");

        $nav = new Nav();

        if ($navHandler->handle($request, $nav, ['isEnabled' => true])) {
            $this->addFlash("success", $translator->trans("alert.nav.success.add", [], "alert"));
            return $this->redirectToRoute("admin_nav_index");
        }

        return $this->render('admin/nav/add.html.twig', [
            'form' => $navHandler->createView()
        ]);
    }
}