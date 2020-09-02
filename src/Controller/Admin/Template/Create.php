<?php

namespace App\Controller\Admin\Template;

use App\Entity\Template;
use App\Handler\TemplateHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class Create extends AbstractController
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
     * @Route("admin/modeles-de-page/nouveau", name="admin_template_add")
     * @param Request $request
     * @param TemplateHandler $templateHandler
     * @return Response
     */
    public function create(Request $request, TemplateHandler $templateHandler): Response
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

}