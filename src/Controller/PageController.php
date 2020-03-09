<?php

namespace App\Controller;

use App\Entity\Page;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("page/")
 */
class PageController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("{slug}", name="page_index", requirements={"slug": "^[a-z0-9]+(?:-[a-z0-9]+)*$"})
     * 
     * @param Page $page
     * @return Response
     */
    public function index(Page $page): Response
    {
        return $this->render('page/index.html.twig', compact('page'));
    }
}