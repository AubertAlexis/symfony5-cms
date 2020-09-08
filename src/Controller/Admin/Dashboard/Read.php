<?php

namespace App\Controller\Admin\Dashboard;

use App\Repository\PageRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Read extends AbstractController
{
    /**
     * @Route("admin/tableau-de-bord", name="admin_dashboard_index")
     * @return Response
     */
    public function read(PageRepository $pageRepository, UserRepository $userRepository): Response
    {
        $pages = $pageRepository->countPages();
        $users = $userRepository->countUsers();

        return $this->render('admin/dashboard/index.html.twig', compact("pages", "users"));
    }
}