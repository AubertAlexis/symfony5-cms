<?php

namespace App\Controller;

use App\Entity\HomePage;
use App\Form\HomePageType;
use App\Repository\AssetRepository;
use App\Repository\PageRepository;
use App\Services\FileManager;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var PageRepository
     */
    private $pageRepository;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var AssetRepository
     */
    private $assetRepository;

    public function __construct(
        TranslatorInterface $translator,
        PageRepository $pageRepository,
        RequestStack $requestStack,
        FileManager $fileManager,
        AssetRepository $assetRepository,
        EntityManagerInterface $manager
    ) {
        $this->translator = $translator;
        $this->pageRepository = $pageRepository;
        $this->request = $requestStack->getCurrentRequest();
        $this->fileManager = $fileManager;
        $this->manager = $manager;
        $this->assetRepository = $assetRepository;
    }

    /**
     * @Route("{id}", name="admin_home_page_edit", requirements={"id": "\d+"})
     * 
     * @param HomePage $homePage
     * @return Response
     */
    public function edit(HomePage $homePage): Response
    {
        $this->denyAccessUnlessGranted("HOME_PAGE_EDIT");

        $form = $this->createForm(HomePageType::class, $homePage);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();

            $this->addFlash("success", $this->translator->trans("alert.homePage.success.edit", [], "alert"));

            return $this->redirectToRoute("admin_home_page_edit", ["id" => $homePage->getId()]);
        }

        return $this->render('admin/homePage/edit.html.twig', [
            "homePage" => $homePage,
            'form' => $form->createView()
        ]);
    }
}
