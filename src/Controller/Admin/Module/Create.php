<?php

namespace App\Controller\Admin\Module;

use App\Entity\Module;
use App\Handler\ModuleHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Create extends AbstractController
{
    /**
     * @Route("admin/modules/nouveau", name="admin_module_add")
     * @param Request $request
     * @param ModuleHandler $moduleHandler
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function create(Request $request, ModuleHandler $moduleHandler, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted("MODULE_ADD");

        $module = new Module();

        if ($moduleHandler->handle($request, $module)) {
            $this->addFlash("success", $translator->trans("alert.module.success.add", [], "alert"));
            return $this->redirectToRoute("admin_setting_edit");
        }

        return $this->render('admin/module/add.html.twig', [
            'form' => $moduleHandler->createView()
        ]);
    }

}