<?php

namespace App\Controller;

use App\Entity\Template;
use App\Handler\TemplateHandler;
use App\Repository\TemplateRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("admin/modeles-de-page/")
 */
class AdminTemplateController extends AbstractController
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
     * @Route("", name="admin_template_index")
     * @param TemplateRepository $templateRepository
     * @return Response
     */
    public function list(TemplateRepository $templateRepository): Response
    {
        $this->denyAccessUnlessGranted("TEMPLATE_LIST");

        return $this->render('admin/template/index.html.twig', [
            'templates' => $templateRepository->findAll()
        ]);
    }

    /**
     * @Route("nouveau", name="admin_template_add")
     * @param Request $request
     * @param TemplateHandler $templateHandler
     * @return Response
     */
    public function add(Request $request, TemplateHandler $templateHandler): Response
    {
        $this->denyAccessUnlessGranted("TEMPLATE_ADD");

        $template = new Template();

        if ($templateHandler->handle($request, $template)) {
            $this->addFlash("success", $this->translator->trans("alert.template.success.add", [], "alert"));
            return $this->redirectToRoute("admin_template_index");
        }

        return $this->render('admin/template/add.html.twig', [
            'form' => $templateHandler->createView()
        ]);
    }

    /**
     * @Route("{id}", name="admin_template_edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param Template $template
     * @param TemplateHandler $templateHandler
     * @return Response
     */
    public function edit(Request $request, Template $template, TemplateHandler $templateHandler): Response
    {
        $this->denyAccessUnlessGranted("TEMPLATE_EDIT");

        if ($templateHandler->handle($request, $template)) {
            $this->addFlash("success", $this->translator->trans("alert.template.success.edit", [], "alert"));
            return $this->redirectToRoute("admin_template_index");
        }

        return $this->render('admin/template/edit.html.twig', [
            "template" => $template,
            'form' => $templateHandler->createView()
        ]);
    }
}
