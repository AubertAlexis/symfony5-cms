<?php

namespace App\Controller;

use App\Entity\ArticleTemplate;
use App\Entity\Asset;
use App\Entity\InternalTemplate;
use App\Entity\Module;
use App\Entity\Page;
use App\Form\ModuleType;
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
 * @Route("admin/modules/")
 */
class AdminModuleController extends AbstractController
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
     * @Route("nouveau", name="admin_module_add")
     * 
     * @return Response
     */
    public function add(): Response
    {
        $this->denyAccessUnlessGranted("MODULE_ADD");

        $module = new Module();

        $form = $this->createForm(ModuleType::class, $module);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($module);
            $this->manager->flush();

            $this->addFlash("success", $this->translator->trans("alert.module.success.add", [], "alert"));

            return $this->redirectToRoute("admin_setting_edit");
        }

        return $this->render('admin/module/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("{id}", name="admin_module_edit", requirements={"id": "\d+"})
     * 
     * @param Module $module
     * @return Response
     */
    public function edit(Module $module): Response
    {
        $this->denyAccessUnlessGranted("MODULE_EDIT");

        $form = $this->createForm(ModuleType::class, $module);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();

            $this->addFlash("success", $this->translator->trans("alert.module.success.edit", [], "alert"));

            return $this->redirectToRoute("admin_setting_edit");
        }

        return $this->render('admin/module/edit.html.twig', [
            "module" => $module,
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
     * Upload image from textEditor
     *
     * @Route("image/{id}", name="admin_page_upload_image", requirements={"id": "\d+"})
     * 
     * @param InternalTemplate $internalTemplate
     * @return Response
     */
    public function uploadImage(InternalTemplate $internalTemplate = null): Response
    {
        $templateName = $internalTemplate->getTemplate()->getKeyname();

        $file = $this->fileManager->uploadFile($this->request->files->get('file'));

        $asset = new Asset();
        $asset->setFileName($file['filename']);

        if ($templateName === "internal") $asset->setInternalTemplate($internalTemplate);

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
     * @param $template
     * @return void
     */
    public function manageAssets($template): void
    {
        $regex = '/uploads\/[a-zA-Z0-9]+\.[a-z]{3,4}/';
        $matches = [];

        if (preg_match_all($regex, $template->getContent(), $matches)) {
            $filenames = array_map(function ($match) {
                return basename($match);
            }, $matches[0]);

            $assets = $this->assetRepository->findAssetToRemove($filenames, $template->getId());

            $this->removeAssets($assets);
        } else if ($template->getAssets()->count() > 0 && $matches) {
            foreach ($template->getAssets() as $asset) {
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

    private function handleTemplate(Page $page)
    {
        $templateName = $page->getTemplate()->getKeyname();

        if ($templateName == 'internal') {
            $template = new InternalTemplate();
            $page->setInternalTemplate($template);
        } else if ($templateName == 'article') {
            $template = new ArticleTemplate();
            $page->setArticleTemplate($template);
        }


        $this->manager->persist($template);
        $this->manager->flush();

        $template->setTemplate($page->getTemplate());
    }
}
