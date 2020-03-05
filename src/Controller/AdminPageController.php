<?php

namespace App\Controller;

use App\Entity\Asset;
use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use App\Services\FileManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    public function __construct(TranslatorInterface $translator, PageRepository $pageRepository, RequestStack $requestStack)
    {
        $this->translator = $translator;
        $this->pageRepository = $pageRepository;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @Route("", name="admin_page_index")
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
     * @Route("nouveau", name="admin_page_add")
     * @Route("{id}", name="admin_page_edit", requirements={"id": "\d+"})
     * 
     * @param Request $request
     * @param Page $page
     * @return Response
     */
    public function edit(Page $page = null): Response
    {
        $this->denyAccessUnlessGranted("PAGE_EDIT", $page);

        $modeName = explode('_', $this->request->attributes->get('_route'))[2];
        $editMode = $modeName === "edit";

        !$editMode && $page = new Page();

        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($this->request);

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

    /**
     * Upload image from textEditor
     *
     * @Route("image/{id}", name="admin_page_upload_image", requirements={"id": "\d+"})
     * 
     * @param FileManager $fileManager
     * @return Response
     */
    public function uploadImage(Page $page, FileManager $fileManager): Response
    {
        $file = $fileManager->uploadFile($this->request->files->get('file'));

        $asset = new Asset();
        $asset->setFileName($file['filename']);
        $asset->setPage($page);

        $this->getDoctrine()->getManager()->persist($asset);
        $this->getDoctrine()->getManager()->flush();

        return $this->json([
                'location' => $file['path']
            ]
        );
    }
}
