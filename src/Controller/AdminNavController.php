<?php

namespace App\Controller;

use App\Entity\Nav;
use App\Entity\NavLink;
use App\Entity\SubNav;
use App\Form\NavLinkItemType;
use App\Form\NavType;
use App\Form\SubNavType;
use App\Repository\NavRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("admin/navigations/")
 */
class AdminNavController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var NavRepository
     */
    private $navRepository;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(
        TranslatorInterface $translator,
        NavRepository $navRepository,
        RequestStack $requestStack,
        EntityManagerInterface $manager
    ) {
        $this->translator = $translator;
        $this->navRepository = $navRepository;
        $this->request = $requestStack->getCurrentRequest();
        $this->manager = $manager;
    }

    /**
     * @Route("", name="admin_nav_index")
     * 
     * @return Response
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted("NAV_LIST");

        return $this->render('admin/nav/index.html.twig', [
            'navs' => $this->navRepository->findAll()
        ]);
    }

    /**
     * @Route("nouveau", name="admin_nav_add")
     * 
     * @return Response
     */
    public function add(): Response
    {
        $this->denyAccessUnlessGranted("NAV_ADD");

        $nav = new Nav();

        $form = $this->createForm(NavType::class, $nav, [
            'isEnabled' => true
        ]);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($nav);

            /**
             * @var NavLink $navLink
             */
            foreach ($nav->getNavLinks() as $index => $navLink) {
                $navLink->setNav($nav);

                $this->manager->persist($navLink);
            }
            
            $this->manager->flush();

            $this->addFlash("success", $this->translator->trans("alert.nav.success.add", [], "alert"));

            return $this->redirectToRoute("admin_nav_index");
        }

        return $this->render('admin/nav/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("{id}", name="admin_nav_edit", requirements={"id": "\d+"})
     * 
     * @param Nav $nav
     * @return Response
     */
    public function edit(Nav $nav): Response
    {
        $this->denyAccessUnlessGranted("NAV_EDIT");

        $form = $this->createForm(NavType::class, $nav, [
            'isEnabled' => $nav->getEnabled()
        ]);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            /**
             * @var NavLink $navLink
             */
            foreach ($nav->getNavLinks() as $navLink) {
                $navLink->setNav($nav);

                $this->manager->persist($navLink);
            }

            $this->manager->flush();

            $this->addFlash("success", $this->translator->trans("alert.nav.success.edit", [], "alert"));

            return $this->redirectToRoute("admin_nav_index");
        }

        return $this->render('admin/nav/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("{id}/suppression", name="admin_nav_delete", requirements={"id": "\d+"}, methods="POST")
     * 
     * @param Request $request
     * @param Nav $nav
     * @return Response
     */
    public function delete(Request $request, Nav $nav) : Response
    {
        $this->denyAccessUnlessGranted("NAV_DELETE");

        if ($this->isCsrfTokenValid("delete-nav", $request->request->get('token'))) {
            $this->manager->remove($nav);

            $this->manager->flush();

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
     * 
     * @param Request $request
     * @param NavLink $navLink
     * @return Response
     */
    public function deleteLink(NavLink $navLink, Request $request) : Response
    {
        $this->denyAccessUnlessGranted("NAV_DELETE");

        if ($this->isCsrfTokenValid("delete-nav-link", $request->request->get('token'))) {

            if ($navLink->getSubNav() !== null) {

                $subNavLinks = $navLink->getSubNav()->getNavlinks();

                /**
                 * @var NavLink $navLink
                 */
                foreach ($subNavLinks as $subNavLink) {
                    $this->manager->remove($subNavLink);
                }

                $this->manager->remove($navLink->getSubNav());

            }

            $this->manager->remove($navLink);

            $this->manager->flush();

            $this->addFlash("success", $this->translator->trans("alert.navLink.success.delete", [], "alert"));

            return $this->redirectToRoute("admin_nav_index");
        }

        $this->addFlash("danger", $this->translator->trans("error.invalidCsrf", [], "error"));

        return $this->redirectToRoute("admin_nav_details", ["id" => $navLink->getNav()->getId()]);
    }

    /**
     * @Route("sous-menu/{id}/nouveau", name="admin_sub_nav_add")
     * 
     * @param NavLink $navLink
     * @return Response
     */
    public function subNavAdd(NavLink $navLink): Response
    {
        $this->denyAccessUnlessGranted("NAV_ADD");

        $subNav = new SubNav();

        $form = $this->createForm(SubNavType::class, $subNav);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subNav->setParent($navLink);
            
            $this->manager->persist($subNav);

            /**
             * @var NavLink $navLinkChildren
             */
            foreach ($subNav->getNavLinks() as $navLinkChildren) {
                $subNav->addNavlink($navLinkChildren);
                $navLinkChildren->setSubNav($subNav);
                $this->manager->persist($navLinkChildren);
            }
            
            $this->manager->flush();

            $this->addFlash("success", $this->translator->trans("alert.subNav.success.add", [], "alert"));

            return $this->redirectToRoute("admin_sub_nav_details", ["id" => $navLink->getId()]);
        }

        return $this->render('admin/nav/subnav/add.html.twig', [
            'form' => $form->createView()
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

        return $this->render('admin/nav/subnav/index.html.twig', [
            'subNavs' => $navLink->getSubNavs()
        ]);
    }
}
