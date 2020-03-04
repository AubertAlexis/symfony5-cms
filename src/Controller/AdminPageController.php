<?php

namespace App\Controller;

use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("admin/")
 */
class AdminPageController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var PageRepository
     */
    private $pageRepository;

    public function __construct(TranslatorInterface $translator, PageRepository $pageRepository)
    {
        $this->translator = $translator;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @Route("pages", name="admin_page_index")
     * 
     * @param PageRepository $pageRepository
     * @return Response
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted("USER_ADMIN", $this->getUser());

        return $this->render('admin/page/index.html.twig', [
            'pages' => $this->pageRepository->findAll()
        ]);
    }

    /**
     * @Route("pages/nouveau", name="admin_page_add")
     * @Route("pages/{id}", name="admin_page_edit", requirements={"id": "\d+"})
     * 
     * @param Request $request
     * @param Page $page
     * @return Response
     */
    public function edit(Request $request, Page $page = null): Response
    {
        $this->denyAccessUnlessGranted("PAGE_EDIT", $page);

        $modeName = explode('_', $request->attributes->get('_route'))[2];
        $editMode = $modeName === "edit";

        !$editMode && $page = new Page();

        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            !$editMode && $this->getDoctrine()->getManager()->persist($page);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("success", $this->translator->trans("alert.page.success.{$modeName}", [], "alert"));

            return $this->redirectToRoute("admin_page_index");
        }

        return $this->render('admin/page/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
