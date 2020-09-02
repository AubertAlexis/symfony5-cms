<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("admin/tableau-de-bord", name="admin_dashboard_index")
     * @return Response
     */
    public function __invoke(): Response
    {
        return $this->render('admin/dashboard/index.html.twig');
    }
}