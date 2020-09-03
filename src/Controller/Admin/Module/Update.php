<?php

namespace App\Controller\Admin\Module;

use App\Entity\Module;
use App\Handler\ModuleHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class Update extends AbstractController
{
    /**
     * @Route("admin/modules/{id}", name="admin_module_edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param Module $module
     * @param ModuleHandler $moduleHandler
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function update(Request $request, Module $module, ModuleHandler $moduleHandler, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted("MODULE_EDIT");

        if ($moduleHandler->handle($request, $module)) {
            $this->addFlash("success", $translator->trans("alert.module.success.edit", [], "alert"));
            return $this->redirectToRoute("admin_setting_edit");
        }

        return $this->render('admin/module/edit.html.twig', [
            "module" => $module,
            'form' => $moduleHandler->createView()
        ]);
    }
}