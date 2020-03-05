<?php

namespace App\Listeners;

use App\Entity\Page;
use App\Repository\AssetRepository;
use App\Services\FileManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class PageListener
{
    /**
     * @var AssetRepository
     */
    private $assetRepository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var FileManager
     */
    private $fileManager;

    public function __construct(AssetRepository $assetRepository, EntityManagerInterface $manager, FileManager $fileManager)
    {
        $this->assetRepository = $assetRepository;
        $this->manager = $manager;
        $this->fileManager = $fileManager;
    }

    public function preUpdate(Page $page, PreUpdateEventArgs $args)
    {
        if ($args->hasChangedField("content")) {
            $regex = '/uploads\/[a-zA-Z0-9]+\.[a-z]{3,4}/';
            $matches = [];

            if (preg_match_all($regex, $args->getNewValue("content"), $matches)) {
                $filenames = array_map(function ($match) {
                    return basename($match);
                }, $matches[0]);

                $assets = $this->assetRepository->findAssetToRemove($filenames, $page->getId());

                foreach ($assets as $asset) {
                    $this->manager->remove($asset);
                    $this->fileManager->removeFile($asset->getFileName());
                }

                $this->manager->flush();
            }
        }
    }
}
