<?php

namespace App\Controller\Admin\Dashboard;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Read extends AbstractController
{
    /**
     * @Route("admin/tableau-de-bord", name="admin_dashboard_index")
     * @return Response
     */
    public function read(): Response
    {
        return $this->render('admin/dashboard/index.html.twig');
    }
}