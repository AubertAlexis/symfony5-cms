<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class FileManager
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * Constructor
     *
     * @param ParameterBagInterface $paramterBag
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
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
        return $this->parameterBag->get('uploads_directory');
    }

    /**
     * Give upload name directory from parameters
     *
     * @return string
     */
    public function getUploadNameDirectory(): string
    {
        return $this->parameterBag->get('uploads_name_directory');
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
