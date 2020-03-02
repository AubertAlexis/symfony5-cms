<?php

namespace App\Controller;

use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/")
 */
class AdminPageController extends AbstractController
{
    /**
     * @Route("pages", name="admin_page_index")
     * 
     * @param PageRepository $pageRepository
     * @return Response
     */
    public function index(PageRepository $pageRepository) : Response
    {
        
        return $this->render('admin/page/index.html.twig', [
            'pages' => $pageRepository->findAll()
        ]);
    }

    /**
     * @Route("pages/{id}", name="admin_page_edit", requirements={"id": "\d+"})
     * 
     * @param Request $request
     * @param Page $page
     * @return Response
     */
    public function edit(Request $request, Page $page): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($form);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("success", $this->translator->trans("alert.page.success.edit", [], "alert"));
        }

        return $this->render('admin/page/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}