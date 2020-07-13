<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileManager
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Upload a file
     *
     * @param UploadedFile $uploadedFile
     * @return array
     */
    public function uploadFile(UploadedFile $uploadedFile): array
    {
        if ($uploadedFile instanceof UploadedFile) {
            $filename = $this->generateUniqueName() . '.' . $uploadedFile->guessExtension();

            $uploadedFile->move(
                $this->getUploadDirectory(),
                $filename
            );

            return [
                'filename' => $filename,
                'path' => "{$this->getUploadNameDirectory()}/{$filename}"
            ];
        }
    }

    /**
     * Generate a unique name for file
     *
     * @return string
     */
    public function generateUniqueName(): string
    {
        return md5(uniqid());
    }

    /**
     * Give upload directory from parameters
     *
     * @return string
     */
    public function getUploadDirectory(): string
    {
        return $this->container->getParameter('uploads_directory');
    }

    /**
     * Give upload name directory from parameters
     *
     * @return string
     */
    public function getUploadNameDirectory(): string
    {
        return $this->container->getParameter('uploads_name_directory');
    }

    /**
     * Remove a file from uploads directory
     *
     * @param string|null $filename
     * @return void
     */
    public function removeFile(?string $filename): void
    {
        if (!empty($filename)) {
            $fileSystem = new Filesystem();

            $fileSystem->remove(
                "{$this->getUploadDirectory()}/{$filename}"
            );
        }
    }
}
