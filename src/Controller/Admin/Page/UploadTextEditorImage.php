<?php

namespace App\Controller\Admin\Page;

use App\Entity\InternalTemplate;
use App\Handler\PageHandler;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class UploadTextEditorImage extends AbstractController
{
    /**
     * @Route("admin/pages/image/{id}", name="admin_page_upload_image", requirements={"id": "\d+"})
     * @param InternalTemplate $internalTemplate
     * @param PageHandler $pageHandler
     * @return JsonResponse
     */
    public function uploadTextEditorImage(InternalTemplate $internalTemplate = null, PageHandler $pageHandler): JsonResponse
    {
        $path = $pageHandler->uploadImage($internalTemplate);

        return $this->json(
            [
                'location' => $path
            ]
        );
    }
}