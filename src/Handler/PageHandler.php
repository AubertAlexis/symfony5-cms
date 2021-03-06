<?php

namespace App\Handler;

use App\Entity\ArticleTemplate;
use App\Entity\Asset;
use App\Entity\ContactTemplate;
use App\Entity\InternalTemplate;
use App\Entity\ListArticlesTemplate;
use App\Entity\Page;
use App\Entity\Seo;
use App\Entity\Template;
use App\Form\PageType;
use App\Repository\AssetRepository;
use App\Services\FileManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;

class PageHandler extends AbstractHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var AssetRepository
     */
    private $assetRepository;

    public function __construct(
        EntityManagerInterface $entityManager, 
        AssetRepository $assetRepository, 
        FileManager $fileManager
    ) {
        $this->entityManager = $entityManager;
        $this->assetRepository = $assetRepository;
        $this->fileManager = $fileManager;
    }

    /**
     * @inheritDoc
     */
    function getFormType(): string
    {
        return PageType::class;
    }

    /**
     * @inheritDoc
     */
    function process($data): void
    {
        /** @var Page $page **/
        $page = $data;

        if($this->entityManager->getUnitOfWork()->getEntityState($data) == UnitOfWork::STATE_NEW) {

            $seo = new Seo();
            $seo->setPage($page);

            $template = $this->handleTemplate($page);

            $this->entityManager->persist($template);
            $this->entityManager->persist($seo);
            $this->entityManager->persist($page);
        } else if ($this->entityManager->getUnitOfWork()->getEntityState($data) == UnitOfWork::STATE_MANAGED) {
            $templateName = $page->getTemplate()->getKeyname();

            if ($templateName === Template::INTERNAL) {
                $uselessAssets = $this->assetRepository->findByInternalTemplate(null);

                $this->removeAssets($uselessAssets);
                $this->manageAssets($page->getInternalTemplate());
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    function remove($data): void
    {
        $templateName = $data->getTemplate()->getKeyname();

        if ($templateName === Template::INTERNAL) {
            $pageAssets = $this->assetRepository->findByInternalTemplate($data->getInternalTemplate());

            $this->removeAssets($pageAssets);
        }

        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }

    /**
     * @param null|InternalTemplate $internalTemplate
     * @return string
     */
    public function uploadImage(?InternalTemplate $internalTemplate): string
    {
        $templateName = $internalTemplate->getTemplate()->getKeyname();

        $file = $this->fileManager->uploadFile($this->getRequest()->files->get('file'));

        $asset = new Asset();
        $asset->setFileName($file['filename']);

        if ($templateName === Template::INTERNAL) $asset->setInternalTemplate($internalTemplate);

        $this->entityManager->persist($asset);
        $this->entityManager->flush();

        return $file["path"];
    }

    /**
     * @param Page $page
     * @return object
     */
    private function handleTemplate(Page $page)
    {
        $templateName = $page->getTemplate()->getKeyname();
        $template = null;
        
        if ($templateName == Template::INTERNAL) {

            $template = new InternalTemplate();
            $page->setInternalTemplate($template);

        } else if ($templateName == Template::ARTICLE) {

            $template = new ArticleTemplate();
            $page->setArticleTemplate($template);

        } else if ($templateName == Template::LIST_ARTICLE) {

            $template = new ListArticlesTemplate();
            $page->setListArticlesTemplate($template);

        } else if ($templateName == Template::CONTACT) {

            $template = new ContactTemplate();
            $page->setContactTemplate($template);

        }

        $template->setTemplate($page->getTemplate());

        return $template;
    }

    /**
     * @param $template
     * @return void
     */
    private function manageAssets($template): void
    {
        $regex = '/uploads\/[a-zA-Z0-9]+\.[a-z]{3,4}/';
        $matches = [];

        if (preg_match_all($regex, $template->getContent(), $matches)) {
            $filenames = array_map(function ($match) {
                return basename($match);
            }, $matches[0]);

            $assets = $this->assetRepository->findAssetToRemove($filenames, $template->getId());

            $this->removeAssets($assets);
        } else if ($template->getAssets()->count() > 0 && $matches) {
            foreach ($template->getAssets() as $asset) {
                $this->entityManager->remove($asset);
                $this->fileManager->removeFile($asset->getFileName());
            }
        }
    }

    /**
     * @param array $assets
     * @return void
     */
    private function removeAssets(array $assets)
    {
        foreach ($assets as $asset) {
            $this->entityManager->remove($asset);
            $this->fileManager->removeFile($asset->getFileName());
        }
    }
}