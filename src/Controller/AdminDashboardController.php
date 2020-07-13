<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/")
 */
class AdminDashboardController extends AbstractController
{
    /**
     * @Route("tableau-de-bord", name="admin_dashboard_index")
     * 
     * @return Response
     */
    public function index() : Response
    {
        return $this->render('admin/dashboard/index.html.twig');
    }
}