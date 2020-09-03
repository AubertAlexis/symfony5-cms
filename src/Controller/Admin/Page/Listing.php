<?php

namespace App\Controller\Admin\Page;

use App\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Listing extends AbstractController
{

    /**
     * @Route("admin/pages", name="admin_page_index")
     * @param PageRepository $pageRepository
     * @return Response
     */
    public function listing(PageRepository $pageRepository): Response
    {
        $this->denyAccessUnlessGranted("PAGE_LIST");

        return $this->render('admin/page/index.html.twig', [
            'pages' => $pageRepository->findAll()
        ]);
    }

}