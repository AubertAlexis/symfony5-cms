<?php

namespace App\Subscribers;

use App\Entity\ArticleTemplate;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageCacheSubscriber implements EventSubscriber
{
    /**
     * @var CacheManager
    */
    private $cacheManager;

    /**
     * @var UploaderHelper
    */
    private $uploaderHelper;

    public function __construct(
        CacheManager $cacheManager,
        UploaderHelper $uploaderHelper
    ) {
        $this->cacheManager = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
    }

    function getSubscribedEvents()
    {
        return [
            "preRemove",
            "preUpdate"
        ];
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        if (!$args->getEntity() instanceof ArticleTemplate) {
            return;
        }

        $this->cacheManager->remove($this->uploaderHelper->asset($args->getEntity(), "imageFile"));
        
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof ArticleTemplate) {
            return;
        }
        
        if ($entity->getImageFile() instanceof UploadedFile) {
            $this->cacheManager->remove($this->uploaderHelper->asset($entity, "imageFile"));
        }
    }
}
