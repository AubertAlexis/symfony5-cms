<?php

namespace App\Controller\Front\Page;

use App\Entity\ArticleTemplate;
use App\Entity\Page;
use App\Entity\Template;
use App\Repository\ArticleTemplateRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Read extends AbstractController
{
    /**
     * @Route("/{slug}", name="page_index", requirements={"slug": "^[a-z0-9]+(?:-[a-z0-9]+)*$"})
     * 
     * @param Page|null $page
     * @return Response
     * @throws NotFoundHttpException
     */
    public function read(Page $page = null): Response
    {
        if (!$page || !$page->getEnabled()) throw new NotFoundHttpException("Not found");

        [$template, $templateName, $articles] = $this->handlePage($page);

        return $this->render("page/{$templateName}.html.twig", array_filter(compact('page', 'template', 'articles')));
    }

    /**
     * @param Page $page
     * @return mixed[]
     * @throws NotFoundHttpException
     */
    private function handlePage(Page $page): array
    {
        if (null === $page->getTemplate()) throw new NotFoundHttpException();

        $templateName = $page->getTemplate()->getKeyname();
        $articles = null;
        $template = null;

        if ($templateName === Template::INTERNAL) {

            $template = $page->getInternalTemplate();

        } else if ($templateName === Template::ARTICLE) {

            $template = $page->getArticleTemplate();

        } else if ($templateName === Template::LIST_ARTICLE) {

            $template = $page->getArticleTemplate();

            /** @var ArticleTemplateRepository **/
            $articleRepository = $this->getDoctrine()->getRepository(ArticleTemplate::class);
            $articles = $articleRepository->findByArticlesEnabled();

        } else if ($templateName === Template::CONTACT) {

            $template = $page->getContactTemplate();
            
        }

        return [$template, $templateName, $articles];
    }
}
