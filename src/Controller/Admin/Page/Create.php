<?php

namespace App\Controller\Admin\Page;

use App\Entity\Page;
use App\Handler\PageHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Create extends AbstractController
{
    /**
     * @Route("admin/pages/nouveau", name="admin_page_add")
     * @param Request $request
     * @param PageHandler $pageHandler
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function create(Request $request, PageHandler $pageHandler, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted("PAGE_ADD");

        $page = new Page();

        if ($pageHandler->handle($request, $page)) {
            $this->addFlash("success", $translator->trans("alert.page.success.add", [], "alert"));
            return $this->redirectToRoute("admin_page_edit", ["id" => $page->getId()]);
        }

        return $this->render('admin/page/add.html.twig', [
            'form' => $pageHandler->createView()
        ]);
    }
}