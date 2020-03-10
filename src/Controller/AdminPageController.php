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
    )
    {
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
     * @param Page $page
     * @param AssetRepository $assetRepository
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
            !$editMode && $this->manager->persist($page);
            $this->manageAssets($page);
            $this->manager->flush();

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

        return $this->json([
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

            foreach ($assets as $asset) {
                $this->manager->remove($asset);
                $this->fileManager->removeFile($asset->getFileName());
            }
        } else if ($page->getAssets()->count() > 0 && $matches) {
            foreach ($page->getAssets() as $asset) {
                $this->manager->remove($asset);
                $this->fileManager->removeFile($asset->getFileName());
            }
        }
    }
}
