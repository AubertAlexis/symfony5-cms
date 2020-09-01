<?php

namespace App\Controller;

use App\Entity\Nav;
use App\Entity\SubNav;
use App\Entity\NavLink;
use App\Handler\NavHandler;
use App\Handler\SubNavHandler;
use App\Handler\NavLinkHandler;
use App\Repository\NavRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("admin/navigations/")
 */
class AdminNavController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator) 
    {
        $this->translator = $translator;
    }

    /**
     * @Route("", name="admin_nav_index")
     * @param NavRepository $navRepository
     * @return Response
     */
    public function index(NavRepository $navRepository): Response
    {
        $this->denyAccessUnlessGranted("NAV_LIST");

        return $this->render('admin/nav/index.html.twig', [
            'navs' => $navRepository->findAll()
        ]);
    }

    /**
     * @Route("nouveau", name="admin_nav_add")
     * @param Request $request
     * @param NavHandler $navHandler
     * @return Response
     */
    public function add(Request $request, NavHandler $navHandler): Response
    {
        $this->denyAccessUnlessGranted("NAV_ADD");

        $nav = new Nav();

        if ($navHandler->handle($request, $nav, ['isEnabled' => true])) {
            $this->addFlash("success", $this->translator->trans("alert.nav.success.add", [], "alert"));
            return $this->redirectToRoute("admin_nav_index");
        }

        return $this->render('admin/nav/add.html.twig', [
            'form' => $navHandler->createView()
        ]);
    }

    /**
     * @Route("{id}", name="admin_nav_edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param Nav $nav
     * @param NavHandler $navHandler
     * @return Response
     */
    public function edit(Request $request, Nav $nav, NavHandler $navHandler): Response
    {
        $this->denyAccessUnlessGranted("NAV_EDIT");

        if ($navHandler->handle($request, $nav, ['isEnabled' => $nav->getEnabled()])) {
            $this->addFlash("success", $this->translator->trans("alert.nav.success.edit", [], "alert"));
            return $this->redirectToRoute("admin_nav_index");
        }

        return $this->render('admin/nav/edit.html.twig', [
            'form' => $navHandler->createView()
        ]);
    }

   /**
    * @Route("{id}/suppression", name="admin_nav_delete", requirements={"id": "\d+"}, methods="POST")
    * @param Request $request
    * @param Nav $nav
    * @param NavHandler $navHandler
    * @param CsrfTokenManagerInterface $tokenManager
    * @return Response
    */
    public function delete(Request $request, Nav $nav, NavHandler $navHandler, CsrfTokenManagerInterface $tokenManager) : Response
    {
        $this->denyAccessUnlessGranted("NAV_DELETE");

        if ($navHandler->validateToken($tokenManager, "delete-nav", $request->request->get('token'), $nav)) {
            $this->addFlash("success", $this->translator->trans("alert.nav.success.delete", [], "alert"));
            return $this->redirectToRoute("admin_nav_index");
        }

        $this->addFlash("danger", $this->translator->trans("error.invalidCsrf", [], "error"));

        return $this->redirectToRoute("admin_nav_index");
    }

     /**
     * @Route("liens/{id}", name="admin_nav_details", requirements={"id": "\d+"})
     * @param Nav $nav
     * @return Response
     */
    public function navDetails(Nav $nav): Response
    {
        $this->denyAccessUnlessGranted("NAV_LIST");

        return $this->render('admin/nav/navlink/index.html.twig', [
            'nav' => $nav
        ]);
    }

    /**
     * @Route("liens/{id}/suppression", name="admin_nav_link_delete", requirements={"id": "\d+"}, methods="POST")
     * @param NavLink $navLink
     * @param Request $request
     * @param NavLinkHandler $navLinkHandler
     * @param CsrfTokenManagerInterface $tokenManager
     * @return Response
     */
    public function deleteLink(NavLink $navLink, Request $request, NavLinkHandler $navLinkHandler, CsrfTokenManagerInterface $tokenManager) : Response
    {
        $this->denyAccessUnlessGranted("NAV_DELETE");

        if ($navLinkHandler->validateToken($tokenManager, "delete-nav-link", $request->request->get('token'), $navLink)) {
            $this->addFlash("success", $this->translator->trans("alert.navLink.success.delete", [], "alert"));
            return $this->redirectToRoute("admin_nav_index");
        }

        $this->addFlash("danger", $this->translator->trans("error.invalidCsrf", [], "error"));

        return $this->redirectToRoute("admin_nav_details", ["id" => $navLink->getNav()->getId()]);
    }

    /**
     * @Route("sous-menu/{id}/nouveau", name="admin_sub_nav_add", requirements={"id": "\d+"})
     * @param Request $request
     * @param NavLink $navLink
     * @param SubNavHandler $subNavHandler
     * @return Response
     */
    public function subNavAdd(Request $request, NavLink $navLink, SubNavHandler $subNavHandler): Response
    {
        $this->denyAccessUnlessGranted("NAV_ADD");

        $subNav = new SubNav();

        if ($subNavHandler->handle($request, $subNav)) {
            $this->addFlash("success", $this->translator->trans("alert.subNav.success.add", [], "alert"));
            return $this->redirectToRoute("admin_sub_nav_details", ["id" => $navLink->getId()]);
        }

        return $this->render('admin/nav/subnav/add.html.twig', [
            'form' => $subNavHandler->createView()
        ]);

    }

    /**
     * @Route("sous-menu/{id}", name="admin_sub_nav_edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param SubNav $subNav
     * @param SubNavHandler $subNavHandler
     * @return Response
     */
    public function subNavEdit(Request $request, SubNav $subNav, SubNavHandler $subNavHandler): Response
    {
        $this->denyAccessUnlessGranted("NAV_EDIT");

        if ($subNavHandler->handle($request, $subNav)) {
            $this->addFlash("success", $this->translator->trans("alert.subNav.success.edit", [], "alert"));
            return $this->redirectToRoute("admin_sub_nav_details", ["id" => $subNav->getParent()->getId()]);
        }

        return $this->render('admin/nav/subnav/edit.html.twig', [
            'form' => $subNavHandler->createView()
        ]);

    }

    /**
    * @Route("sous-menu/liens/{id}", name="admin_sub_nav_details", requirements={"id": "\d+"})
    * @param NavLink $navLink
    * @return Response
    */
    public function subNavDetails(NavLink $navLink): Response
    {
        $this->denyAccessUnlessGranted("NAV_LIST");

        if ($navLink->getNav() !== null) {
            $navLinkRoute = "admin_nav_details";
            $navLinkId = $navLink->getNav()->getId();
        } else {
            $navLinkRoute = "admin_sub_nav_details";
            $navLinkId = $navLink->getSubNav()->getParent()->getId();
        }

        $returnUrl = $this->generateUrl($navLinkRoute, [
            "id" => $navLinkId
        ]);
        
        return $this->render('admin/nav/subnav/index.html.twig', [
            'returnUrl' => $returnUrl,
            'navLink' => $navLink,
            'subNavs' => $navLink->getSubNavs()
        ]);
    }
}
