<?php

namespace App\Controller\Admin\Page;

use App\Entity\Page;
use App\Handler\PageHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Update extends AbstractController
{
    /**
     * @Route("/admin/pages/{id}", name="admin_page_edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param Page $page
     * @param PageHandler $pageHandler
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function update(Request $request, Page $page, PageHandler $pageHandler, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted("PAGE_EDIT");

        if ($pageHandler->handle($request, $page)) {
            $this->addFlash("success", $translator->trans("alert.page.success.edit", [], "alert"));
            return $this->redirectToRoute("admin_page_index");
        }

        return $this->render('admin/page/edit.html.twig', [
            "page" => $page,
            'form' => $pageHandler->createView()
        ]);
    }
}