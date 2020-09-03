<?php

namespace App\Controller\Admin\Template;

use App\Repository\TemplateRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Listing extends AbstractController
{

    /**
     * @Route("admin/modeles-de-page/", name="admin_template_index")
     * @param TemplateRepository $templateRepository
     * @return Response
     */
    public function listing(TemplateRepository $templateRepository): Response
    {
        $this->denyAccessUnlessGranted("TEMPLATE_LIST");

        return $this->render('admin/template/index.html.twig', [
            'templates' => $templateRepository->findAll()
        ]);
    }

}