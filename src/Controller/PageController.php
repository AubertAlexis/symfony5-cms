<?php

namespace App\Controller;

use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/")
 */
class PageController extends AbstractController
{
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
    public function index(Page $page = null): Response
    {
        if (!$page || !$page->getEnabled()) throw new NotFoundHttpException("Not found");

        $templateName = $page->getTemplate()->getKeyname();
        
        if ($templateName === "internal") {
            $template = $page->getInternalTemplate();
        } else if ($templateName === "article") {
            $template = $page->getArticleTemplate();
        }

        return $this->render("page/{$templateName}.html.twig", compact('page', 'template'));
    }
}
