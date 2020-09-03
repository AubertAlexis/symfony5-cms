<?php

namespace App\Controller\Admin\HomePage;

use App\Handler\HomeHandler;
use App\Repository\HomePageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Contracts\Translation\TranslatorInterface;

class Update extends AbstractController
{
    /**
     * @Route("admin/accueil/", name="admin_home_page_edit")
     * @param Request $request
     * @param HomeHandler $homeHandler
     * @param HomePageRepository $homePageRepository
     * @param TranslatorInterface $translator
     * @return Response
     * @throws NotFoundResourceException
     */
    public function update(
        Request $request, 
        HomeHandler $homeHandler, 
        HomePageRepository $homePageRepository, 
        TranslatorInterface $translator
    ): Response
    {
        $this->denyAccessUnlessGranted("HOME_PAGE_EDIT");

        $homePage = $homePageRepository->findBy([], [
            "id" => "DESC"
        ], 1, 0)[0];

        if ($homePage == null) throw new NotFoundResourceException("There is no HomePage entry on the database. You need 1, run fixtures to fix this");

        if ($homeHandler->handle($request, $homePage)) {
            $this->addFlash("success", $translator->trans("alert.homePage.success.edit", [], "alert"));
            return $this->redirectToRoute("admin_home_page_edit", ["id" => $homePage->getId()]);
        }

        return $this->render('admin/homePage/edit.html.twig', [
            "homePage" => $homePage,
            'form' => $homeHandler->createView()
        ]);
    }
}
