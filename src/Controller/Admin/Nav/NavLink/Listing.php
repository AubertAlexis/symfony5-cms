<?php

namespace App\Controller\Admin\Nav\NavLink;

use App\Entity\Nav;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Listing extends AbstractController
{
    
     /**
     * @Route("admin/navigations/liens/{id}", name="admin_nav_details", requirements={"id": "\d+"})
     * @param Nav $nav
     * @return Response
     */
    public function listing(Nav $nav): Response
    {
        $this->denyAccessUnlessGranted("NAV_LIST");

        return $this->render('admin/nav/navlink/index.html.twig', [
            'nav' => $nav
        ]);
    }

}