<?php

namespace App\Controller;

use App\Entity\ArticleTemplate;
use App\Entity\Asset;
use App\Entity\InternalTemplate;
use App\Entity\Page;
use App\Entity\Seo;
use App\Form\PageType;
use App\Handler\PageHandler;
use App\Repository\AssetRepository;
use App\Repository\PageRepository;
use App\Services\FileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("admin/pages/")
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

    /**
     * @var Request
     */
    private $request;

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var AssetRepository
     */
    private $assetRepository;

    public function __construct(
        TranslatorInterface $translator,
        PageRepository $pageRepository,
        RequestStack $requestStack,
        FileManager $fileManager,
        AssetRepository $assetRepository,
        EntityManagerInterface $manager
    ) {
        $this->translator = $translator;
        $this->pageRepository = $pageRepository;
        $this->request = $requestStack->getCurrentRequest();
        $this->fileManager = $fileManager;
        $this->manager = $manager;
        $this->assetRepository = $assetRepository;
    }

    /**
     * @Route("", name="admin_page_index")
     * 
     * @return Response
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted("PAGE_LIST");

        return $this->render('admin/page/index.html.twig', [
            'pages' => $this->pageRepository->findAll()
        ]);
    }

    /**
     * @Route("nouveau", name="admin_page_add")
     * 
     * @param PageHandler $pageHandler
     * @return Response
     */
    public function add(PageHandler $pageHandler): Response
    {
        $this->denyAccessUnlessGranted("PAGE_ADD");

        $page = new Page();

        if ($pageHandler->handle($this->request, $page)) {
            $this->addFlash("success", $this->translator->trans("alert.page.success.add", [], "alert"));
            return $this->redirectToRoute("admin_page_edit", ["id" => $page->getId()]);
        }

        return $this->render('admin/page/add.html.twig', [
            'form' => $pageHandler->createView()
        ]);
    }

    /**
     * @Route("{id}", name="admin_page_edit", requirements={"id": "\d+"})
     * 
     * @param Page $page
     * @param PageHandler $pageHandler
     * @return Response
     */
    public function edit(Page $page, PageHandler $pageHandler): Response
    {
        $this->denyAccessUnlessGranted("PAGE_EDIT");

        if ($pageHandler->handle($this->request, $page)) {
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
     * 
     * @param Request $request
     * @param Page $page
     * @return Response
     */
    public function delete(Request $request, Page $page): Response
    {
        $this->denyAccessUnlessGranted("PAGE_DELETE");

        if ($this->isCsrfTokenValid("delete-page", $request->request->get('token'))) {
            $templateName = $page->getTemplate()->getKeyname();

            if ($templateName === "internal") {
                $pageAssets = $this->assetRepository->findByInternalTemplate($page->getInternalTemplate());

                $this->removeAssets($pageAssets);
            }

            $this->manager->remove($page);
            $this->manager->flush();

            $this->addFlash("success", $this->translator->trans("alert.page.success.delete", [], "alert"));
            return $this->redirectToRoute("admin_page_index");
        }

        $this->addFlash("danger", $this->translator->trans("error.invalidCsrf", [], "error"));

        return $this->redirectToRoute("admin_page_index");
    }

    /**
     * @Route("image/{id}", name="admin_page_upload_image", requirements={"id": "\d+"})
     * 
     * @param InternalTemplate $internalTemplate
     * @param PageHandler $pageHandler
     * @return Response
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
