<?php

namespace App\Controller\Admin\Nav\SubNav;

use App\Entity\Nav;
use App\Entity\SubNav;
use App\Handler\NavHandler;
use App\Handler\SubNavHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class Update extends AbstractController
{
    /**
     * @Route("admin/navigations/sous-menu/{id}", name="admin_sub_nav_edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param SubNav $subNav
     * @param SubNavHandler $subNavHandler
     * @param TranslatorInterface $translator
     * @return Response
     * @throws NotFoundResourceException
     */
    public function update(
        Request $request, 
        SubNav $subNav, 
        SubNavHandler $subNavHandler,
        TranslatorInterface $translator
    ): Response
    {
        $this->denyAccessUnlessGranted("NAV_EDIT");

        if ($subNavHandler->handle($request, $subNav)) {
            $this->addFlash("success", $translator->trans("alert.subNav.success.edit", [], "alert"));
            if(null === $subNav->getParent()) throw new NotFoundResourceException();
            return $this->redirectToRoute("admin_sub_nav_details", ["id" => $subNav->getParent()->getId()]);
        }

        return $this->render('admin/nav/subnav/edit.html.twig', [
            'form' => $subNavHandler->createView()
        ]);

    }

}