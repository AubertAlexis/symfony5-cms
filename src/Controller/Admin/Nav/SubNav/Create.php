<?php

namespace App\Controller\Admin\Nav\SubNav;

use App\Entity\Nav;
use App\Entity\NavLink;
use App\Entity\SubNav;
use App\Handler\NavHandler;
use App\Handler\SubNavHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Create extends AbstractController
{
    /**
     * @Route("admin/navigations/sous-menu/{id}/nouveau", name="admin_sub_nav_add", requirements={"id": "\d+"})
     * @param Request $request
     * @param NavLink $navLink
     * @param SubNavHandler $subNavHandler
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function create(
        Request $request,
        NavLink $navLink, 
        SubNavHandler $subNavHandler, 
        TranslatorInterface $translator
    ): Response
    {
        $this->denyAccessUnlessGranted("NAV_ADD");

        $subNav = new SubNav();

        if ($subNavHandler->handle($request, $subNav)) {
            $this->addFlash("success", $translator->trans("alert.subNav.success.add", [], "alert"));
            return $this->redirectToRoute("admin_sub_nav_details", ["id" => $navLink->getId()]);
        }

        return $this->render('admin/nav/subnav/add.html.twig', [
            'form' => $subNavHandler->createView()
        ]);

    }
}