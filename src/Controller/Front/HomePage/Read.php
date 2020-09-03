<?php

namespace App\Controller\Front\HomePage;

use App\Repository\HomePageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Read extends AbstractController
{
    /**
     * @Route("/", name="home_index")
     * @param HomePageRepository $homePageRepository
     * @return Response
     */
    public function read(HomePageRepository $homePageRepository): Response
    {
        return $this->render("home/index.html.twig", [
            "home" => $homePageRepository->findBy([], ["id" => "DESC"], 1, 0)[0]
        ]);
    }
}
