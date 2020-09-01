<?php

namespace App\Controller;

use App\Entity\Page;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/")
 */
class PageController extends AbstractController
{
    /**
     * @Route("{slug}", name="page_index", requirements={"slug": "^[a-z0-9]+(?:-[a-z0-9]+)*$"})
     * 
     * @param Page|null $page
     * @return Response
     * @throws NotFoundHttpException
     */
    public function __invoke(Page $page = null): Response
    {
        if (!$page || !$page->getEnabled()) throw new NotFoundHttpException("Not found");

        [$template, $templateName] = $this->handlePage($page);

        return $this->render("page/{$templateName}.html.twig", compact('page', 'template'));
    }

    /**
     * @param Page $page
     * @return array
     */
    private function handlePage(Page $page): array
    {
        $templateName = $page->getTemplate()->getKeyname();
        
        if ($templateName === "internal") {
            $template = $page->getInternalTemplate();
        } else if ($templateName === "article") {
            $template = $page->getArticleTemplate();
        }

        return [$template, $templateName];
    }
}
