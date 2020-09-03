<?php

namespace App\Controller\Admin\Nav;

use App\Repository\NavRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Listing extends AbstractController
{
    
     /**
     * @Route("", name="admin_nav_index")
     * @param NavRepository $navRepository
     * @return Response
     */
    public function listing(NavRepository $navRepository): Response
    {
        $this->denyAccessUnlessGranted("NAV_LIST");

        return $this->render('admin/nav/index.html.twig', [
            'navs' => $navRepository->findAll()
        ]);
    }

}