<?php

namespace App\Controller\Admin\Template;

use App\Entity\Template;
use App\Handler\TemplateHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class Update extends AbstractController
{
    /**
     * @Route("admin/modeles-de-page//{id}", name="admin_template_edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param Template $template
     * @param TemplateHandler $templateHandler
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function update(Request $request, Template $template, TemplateHandler $templateHandler, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted("TEMPLATE_EDIT");

        if ($templateHandler->handle($request, $template)) {
            $this->addFlash("success", $translator->trans("alert.template.success.edit", [], "alert"));
            return $this->redirectToRoute("admin_template_index");
        }

        return $this->render('admin/template/edit.html.twig', [
            "template" => $template,
            'form' => $templateHandler->createView()
        ]);
    }

}