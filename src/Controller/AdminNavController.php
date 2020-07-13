<?php

namespace App\Controller;

use App\Entity\Asset;
use App\Entity\Nav;
use App\Entity\Page;
use App\Form\NavType;
use App\Form\PageType;
use App\Repository\AssetRepository;
use App\Repository\NavRepository;
use App\Repository\PageRepository;
use App\Services\FileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
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

        $form = $this->createForm(NavType::class, $nav);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($nav);
            $this->manager->flush();

            $this->addFlash("success", $this->translator->trans("alert.nav.success.add", [], "alert"));

            return $this->redirectToRoute("admin_nav_index");
        }

        return $this->render('admin/nav/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // /**
    //  * @Route("{id}", name="admin_page_edit", requirements={"id": "\d+"})
    //  * 
    //  * @param Page $page
    //  * @return Response
    //  */
    // public function edit(Page $page): Response
    // {
    //     $this->denyAccessUnlessGranted("PAGE_EDIT", $page);

    //     $form = $this->createForm(PageType::class, $page);

    //     $form->handleRequest($this->request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $uselessAssets = $this->assetRepository->findByPage(null);

    //         $this->removeAssets($uselessAssets);
    //         $this->manageAssets($page);
            
    //         $this->manager->flush();

    //         $this->addFlash("success", $this->translator->trans("alert.page.success.edit", [], "alert"));

    //         return $this->redirectToRoute("admin_page_index");
    //     }

    //     return $this->render('admin/page/edit.html.twig', [
    //         'form' => $form->createView()
    //     ]);
    // }

    // /**
    //  * @Route("{id}/suppression", name="admin_page_delete", requirements={"id": "\d+"}, methods="POST")
    //  * 
    //  * @param Request $request
    //  * @param Page $page
    //  * @return Response
    //  */
    // public function delete(Request $request, Page $page) : Response
    // {
    //     $this->denyAccessUnlessGranted("PAGE_DELETE", $page);

    //     if ($this->isCsrfTokenValid("delete-page", $request->request->get('token'))) {
    //         $pageAssets = $this->assetRepository->findByPage($page->getId());

    //         $this->removeAssets($pageAssets);
    //         $this->manager->remove($page);

    //         $this->manager->flush();

    //         $this->addFlash("success", $this->translator->trans("alert.page.success.delete", [], "alert"));

    //         return $this->redirectToRoute("admin_page_index");
    //     }

    //     $this->addFlash("danger", $this->translator->trans("error.invalidCsrf", [], "error"));

    //     return $this->redirectToRoute("admin_page_index");
    // }
}
