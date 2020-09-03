<?php

namespace App\Controller\Admin\Page;

use App\Entity\Page;
use App\Handler\PageHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class Delete extends AbstractController
{
    /**
     * @Route("admin/pages/{id}/suppression", name="admin_page_delete", requirements={"id": "\d+"}, methods="POST")
     * @param Request $request
     * @param Page $page
     * @param PageHandler $pageHandler
     * @param CsrfTokenManagerInterface $tokenManager
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function delete(
        Request $request, 
        Page $page, 
        PageHandler $pageHandler, 
        CsrfTokenManagerInterface $tokenManager,
        TranslatorInterface $translator
    ): Response 
    {
        $this->denyAccessUnlessGranted("PAGE_DELETE");

        if ($pageHandler->validateToken($tokenManager, "delete-page", $request->request->get('token'), $page)) {
            $this->addFlash("success", $translator->trans("alert.page.success.delete", [], "alert"));
            return $this->redirectToRoute("admin_page_index");
        }

        $this->addFlash("danger", $translator->trans("error.invalidCsrf", [], "error"));

        return $this->redirectToRoute("admin_page_index");
    }
}