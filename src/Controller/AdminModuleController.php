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
     * @Route("changement/{id}", name="admin_module_change", requirements={"id": "\d+"})
     * 
     * @param Module $module
     * @return Response
     */
    public function change(Module $module): Response
    {
        $this->denyAccessUnlessGranted("MODULE_MANAGE");

        $module->setEnabled(!$module->getEnabled());
        
        $this->manager->flush();
        
        $newState = $module->getEnabled() ? "enabled" : "disabled";

        $this->addFlash("success", $this->translator->trans("alert.module.success.{$newState}", [], "alert"));

        return $this->redirectToRoute("admin_setting_edit");
    }
}
