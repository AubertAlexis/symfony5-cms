<?php

namespace App\Controller\Admin\Nav\NavLink;

use App\Entity\NavLink;
use App\Handler\NavLinkHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class Delete extends AbstractController
{
    /**
     * @Route("admin/navigations/liens/{id}/suppression", name="admin_nav_link_delete", requirements={"id": "\d+"}, methods="POST")
     * @param NavLink $navLink
     * @param Request $request
     * @param NavLinkHandler $navLinkHandler
     * @param CsrfTokenManagerInterface $tokenManager
     * @param TranslatorInterface $translator
     * @return Response
     * @throws NoSuchPropertyException
     */
    public function delete(
        NavLink $navLink, 
        Request $request, 
        NavLinkHandler $navLinkHandler, 
        CsrfTokenManagerInterface $tokenManager,
        TranslatorInterface $translator
    ) : Response
    {
        $this->denyAccessUnlessGranted("NAV_DELETE");

        if ($navLinkHandler->validateToken($tokenManager, "delete-nav-link", $request->request->get('token'), $navLink)) {
            $this->addFlash("success", $translator->trans("alert.navLink.success.delete", [], "alert"));
            return $this->redirectToRoute("admin_nav_index");
        }

        $this->addFlash("danger", $translator->trans("error.invalidCsrf", [], "error"));

        if (null === $navLink->getNav()) throw new NoSuchPropertyException();

        return $this->redirectToRoute("admin_nav_details", ["id" => $navLink->getNav()->getId()]);
    }
}