<?php

namespace App\Controller;

use App\Entity\Asset;
use App\Entity\Page;
use App\Form\PageType;
use App\Repository\AssetRepository;
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
     * @return Response
     */
    public function add(): Response
    {
        $this->denyAccessUnlessGranted("PAGE_ADD");

        $page = new Page();

        $form = $this->createForm(PageType::class, $page);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($page);
            $this->manager->flush();

            $this->addFlash("success", $this->translator->trans("alert.page.success.add", [], "alert"));

            return $this->redirectToRoute("admin_page_index");
        }

        return $this->render('admin/page/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("{id}", name="admin_page_edit", requirements={"id": "\d+"})
     * 
     * @param Page $page
     * @return Response
     */
    public function edit(Page $page): Response
    {
        $this->denyAccessUnlessGranted("PAGE_EDIT");

        $form = $this->createForm(PageType::class, $page);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uselessAssets = $this->assetRepository->findByPage(null);

            $this->removeAssets($uselessAssets);
            $this->manageAssets($page);
            
            $this->manager->flush();

            $this->addFlash("success", $this->translator->trans("alert.page.success.edit", [], "alert"));

            return $this->redirectToRoute("admin_page_index");
        }

        return $this->render('admin/page/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("{id}/suppression", name="admin_page_delete", requirements={"id": "\d+"}, methods="POST")
     * 
     * @param Request $request
     * @param Page $page
     * @return Response
     */
    public function delete(Request $request, Page $page) : Response
    {
        $this->denyAccessUnlessGranted("PAGE_DELETE");

        if ($this->isCsrfTokenValid("delete-page", $request->request->get('token'))) {
            $pageAssets = $this->assetRepository->findByPage($page->getId());

            $this->removeAssets($pageAssets);
            $this->manager->remove($page);

            $this->manager->flush();

            $this->addFlash("success", $this->translator->trans("alert.page.success.delete", [], "alert"));

            return $this->redirectToRoute("admin_page_index");
        }

        $this->addFlash("danger", $this->translator->trans("error.invalidCsrf", [], "error"));

        return $this->redirectToRoute("admin_page_index");
    }

    /**
     * Upload image from textEditor
     *
     * @Route("image/{id}", name="admin_page_upload_image", requirements={"id": "\d+"})
     * 
     * @param Page $page
     * @return Response
     */
    public function uploadImage(Page $page = null): Response
    {
        $file = $this->fileManager->uploadFile($this->request->files->get('file'));

        $asset = new Asset();
        $asset->setFileName($file['filename']);

        if ($page) $asset->setPage($page);

        $this->manager->persist($asset);
        $this->manager->flush();

        return $this->json(
            [
                'location' => $file['path']
            ]
        );
    }

    /**
     * Manage assets from the content
     *
     * @param Page $page
     * @return void
     */
    public function manageAssets(Page $page): void
    {
        $regex = '/uploads\/[a-zA-Z0-9]+\.[a-z]{3,4}/';
        $matches = [];

        if (preg_match_all($regex, $page->getContent(), $matches)) {
            $filenames = array_map(function ($match) {
                return basename($match);
            }, $matches[0]);

            $assets = $this->assetRepository->findAssetToRemove($filenames, $page->getId());

            $this->removeAssets($assets);
        } else if ($page->getAssets()->count() > 0 && $matches) {
            foreach ($page->getAssets() as $asset) {
                $this->manager->remove($asset);
                $this->fileManager->removeFile($asset->getFileName());
            }
        }
    }

    /**
     * Remove assets from database and upload dir
     *
     * @param array $assets
     * @return void
     */
    private function removeAssets(array $assets)
    {
        foreach ($assets as $asset) {
            $this->manager->remove($asset);
            $this->fileManager->removeFile($asset->getFileName());
        }
    }
}
