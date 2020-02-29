<?php

namespace App\Controller;

use App\Repository\UserRepository;
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
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository) : Response
    {
        return $this->render('admin/dashboard/index.html.twig',[
            'users' => $userRepository->countUser()
        ]);
    }
}