<?php

namespace App\Controller;

use App\Entity\Template;
use App\Form\TemplateType;
use App\Repository\AssetRepository;
use App\Repository\TemplateRepository;
use App\Services\FileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("admin/modeles-de-page/")
 */
class AdminTemplateController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var TemplateRepository
     */
    private $templateRepository;

    /**
     * @var Request
     */
    private $request;
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(
        TranslatorInterface $translator,
        TemplateRepository $templateRepository,
        RequestStack $requestStack,
        FileManager $fileManager,
        AssetRepository $assetRepository,
        EntityManagerInterface $manager
    ) {
        $this->translator = $translator;
        $this->templateRepository = $templateRepository;
        $this->request = $requestStack->getCurrentRequest();
        $this->fileManager = $fileManager;
        $this->manager = $manager;
        $this->assetRepository = $assetRepository;
    }


    /**
     * @Route("", name="admin_template_index")
     * 
     * @return Response
     */
    public function list(): Response
    {
        $this->denyAccessUnlessGranted("TEMPLATE_LIST");

        return $this->render('admin/template/index.html.twig', [
            'templates' => $this->templateRepository->findAll()
        ]);
    }

    /**
     * @Route("nouveau", name="admin_template_add")
     * 
     * @return Response
     */
    public function add(): Response
    {
        $this->denyAccessUnlessGranted("TEMPLATE_ADD");

        $template = new Template();

        $form = $this->createForm(TemplateType::class, $template);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($template);
            $this->manager->flush();

            $this->addFlash("success", $this->translator->trans("alert.template.success.add", [], "alert"));

            return $this->redirectToRoute("admin_template_index");
        }

        return $this->render('admin/template/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("{id}", name="admin_template_edit", requirements={"id": "\d+"})
     * 
     * @param Template $template
     * @return Response
     */
    public function edit(Template $template): Response
    {
        $this->denyAccessUnlessGranted("TEMPLATE_EDIT");

        $form = $this->createForm(TemplateType::class, $template);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();

            $this->addFlash("success", $this->translator->trans("alert.template.success.edit", [], "alert"));

            return $this->redirectToRoute("admin_template_index");
        }

        return $this->render('admin/template/edit.html.twig', [
            "template" => $template,
            'form' => $form->createView()
        ]);
    }
}
