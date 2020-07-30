<?php 

namespace App\Controller;

use App\Repository\HomePageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_index")
     */
    public function index(HomePageRepository $homePageRepository)
    {
        return $this->render("home/index.html.twig", [
            "home" => $homePageRepository->findAll()[0]
        ]);
    }
}