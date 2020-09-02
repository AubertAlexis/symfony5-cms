<?php

namespace App\Controller;

use App\Entity\Module;
use App\Handler\ModuleHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("nouveau", name="admin_module_add")
     * @param Request $request
     * @param ModuleHandler $moduleHandler
     * @return Response
     */
    public function add(Request $request, ModuleHandler $moduleHandler): Response
    {
        $this->denyAccessUnlessGranted("MODULE_ADD");

        $module = new Module();

        if ($moduleHandler->handle($request, $module)) {
            $this->addFlash("success", $this->translator->trans("alert.module.success.add", [], "alert"));
            return $this->redirectToRoute("admin_setting_edit");
        }

        return $this->render('admin/module/add.html.twig', [
            'form' => $moduleHandler->createView()
        ]);
    }

    /**
     * @Route("{id}", name="admin_module_edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param Module $module
     * @param ModuleHandler $moduleHandler
     * @return Response
     */
    public function edit(Request $request, Module $module, ModuleHandler $moduleHandler): Response
    {
        $this->denyAccessUnlessGranted("MODULE_EDIT");

        if ($moduleHandler->handle($request, $module)) {
            $this->addFlash("success", $this->translator->trans("alert.module.success.edit", [], "alert"));
            return $this->redirectToRoute("admin_setting_edit");
        }

        return $this->render('admin/module/edit.html.twig', [
            "module" => $module,
            'form' => $moduleHandler->createView()
        ]);
    }

    /**
     * @Route("changement/{id}", name="admin_module_change", requirements={"id": "\d+"})
     * 
     * @param EntityManagerInterface $entityManager
     * @param Module $module
     * @return Response
     */
    public function change(EntityManagerInterface $entityManager, Module $module): Response
    {
        $this->denyAccessUnlessGranted("MODULE_MANAGE");

        $module->setEnabled(!$module->getEnabled());

        $entityManager->flush();

        $newState = $module->getEnabled() ? "enabled" : "disabled";
        
        $this->addFlash("success", $this->translator->trans("alert.module.success.{$newState}", [], "alert"));
        return $this->redirectToRoute("admin_setting_edit");
    }
}
