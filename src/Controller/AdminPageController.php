<?php

namespace App\Controller;

use App\Entity\Page;
use App\Handler\PageHandler;
use App\Entity\InternalTemplate;
use App\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("admin/pages/")
 */
class AdminPageController extends AbstractController
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
     * @Route("", name="admin_page_index")
     * @param PageRepository $pageRepository
     * @return Response
     */
    public function index(PageRepository $pageRepository): Response
    {
        $this->denyAccessUnlessGranted("PAGE_LIST");

        return $this->render('admin/page/index.html.twig', [
            'pages' => $pageRepository->findAll()
        ]);
    }

    /**
     * @Route("nouveau", name="admin_page_add")
     * @param Request $request
     * @param PageHandler $pageHandler
     * @return Response
     */
    public function add(Request $request, PageHandler $pageHandler): Response
    {
        $this->denyAccessUnlessGranted("PAGE_ADD");

        $page = new Page();

        if ($pageHandler->handle($request, $page)) {
            $this->addFlash("success", $this->translator->trans("alert.page.success.add", [], "alert"));
            return $this->redirectToRoute("admin_page_edit", ["id" => $page->getId()]);
        }

        return $this->render('admin/page/add.html.twig', [
            'form' => $pageHandler->createView()
        ]);
    }

    /**
     * @Route("{id}", name="admin_page_edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param Page $page
     * @param PageHandler $pageHandler
     * @return Response
     */
    public function edit(Request $request, Page $page, PageHandler $pageHandler): Response
    {
        $this->denyAccessUnlessGranted("PAGE_EDIT");

        if ($pageHandler->handle($request, $page)) {
            $this->addFlash("success", $this->translator->trans("alert.page.success.edit", [], "alert"));
            return $this->redirectToRoute("admin_page_index");
        }

        return $this->render('admin/page/edit.html.twig', [
            "page" => $page,
            'form' => $pageHandler->createView()
        ]);
    }

    /**
     * @Route("{id}/suppression", name="admin_page_delete", requirements={"id": "\d+"}, methods="POST")
     * @param Request $request
     * @param Page $page
     * @param PageHandler $pageHandler
     * @param CsrfTokenManagerInterface $tokenManager
     * @return Response
     */
    public function delete(
        Request $request, 
        Page $page, 
        PageHandler $pageHandler, 
        CsrfTokenManagerInterface $tokenManager
    ): Response 
    {
        $this->denyAccessUnlessGranted("PAGE_DELETE");

        if ($pageHandler->validateToken($tokenManager, "delete-page", $request->request->get('token'), $page)) {
            $this->addFlash("success", $this->translator->trans("alert.page.success.delete", [], "alert"));
            return $this->redirectToRoute("admin_page_index");
        }

        $this->addFlash("danger", $this->translator->trans("error.invalidCsrf", [], "error"));

        return $this->redirectToRoute("admin_page_index");
    }

    /**
     * @Route("image/{id}", name="admin_page_upload_image", requirements={"id": "\d+"})
     * @param InternalTemplate $internalTemplate
     * @param PageHandler $pageHandler
     * @return JsonResponse
     */
    public function uploadImage(InternalTemplate $internalTemplate = null, PageHandler $pageHandler): JsonResponse
    {
        $path = $pageHandler->uploadImage($internalTemplate);

        return $this->json(
            [
                'location' => $path
            ]
        );
    }
}
