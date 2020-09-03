<?php

namespace App\Controller\Admin\Module;

use App\Entity\Module;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangeState extends AbstractController
{

    /**
     * @Route("admin/modules/changement/{id}", name="admin_module_change", requirements={"id": "\d+"})
     * 
     * @param EntityManagerInterface $entityManager
     * @param Module $module
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function changeState(EntityManagerInterface $entityManager, Module $module, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted("MODULE_MANAGE");

        $module->setEnabled(!$module->getEnabled());

        $entityManager->flush();

        $newState = $module->getEnabled() ? "enabled" : "disabled";
        
        $this->addFlash("success", $translator->trans("alert.module.success.{$newState}", [], "alert"));
        return $this->redirectToRoute("admin_setting_edit");
    }

}