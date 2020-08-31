<?php

namespace App\Controller;

use App\Entity\HomePage;
use App\Form\HomePageType;
use App\Handler\HomeHandler;
use App\Repository\HomePageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("admin/accueil/")
 */
class AdminHomePageController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var HomePageRepository
     */
    private $homePageRepository;

    public function __construct(
        TranslatorInterface $translator,
        RequestStack $requestStack,
        EntityManagerInterface $manager,
        HomePageRepository $homePageRepository
    ) {
        $this->translator = $translator;
        $this->request = $requestStack->getCurrentRequest();
        $this->manager = $manager;
        $this->homePageRepository = $homePageRepository;
    }

    /**
     * @Route("", name="admin_home_page_edit")
     * 
     * @return Response
     */
    public function edit(HomeHandler $homeHandler): Response
    {
        $this->denyAccessUnlessGranted("HOME_PAGE_EDIT");

        $homePage = $this->getHomePage();

        if ($homeHandler->handle($this->request, $homePage)) {
            $this->addFlash("success", $this->translator->trans("alert.homePage.success.edit", [], "alert"));
            return $this->redirectToRoute("admin_home_page_edit", ["id" => $homePage->getId()]);
        }

        return $this->render('admin/homePage/edit.html.twig', [
            "homePage" => $homePage,
            'form' => $homeHandler->createView()
        ]);
    }

    /**
     * @return HomePage
     * @throws NonUniqueResultException
     */
    private function getHomePage()
    {
        $homePages = $this->homePageRepository->findAll();

        if (sizeof($homePages) !== 1) {
            throw new NonUniqueResultException("There is an error in the database, we were expecting a single entry for the HomePage table, zero or more than one was found");
        }

        return $homePages[0];
    }
}
