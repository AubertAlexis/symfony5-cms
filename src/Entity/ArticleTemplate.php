<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleTemplateRepository")
 * @Vich\Uploadable()
 */
class ArticleTemplate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\File(
     *  mimeTypes={"image/jpeg", "image/png"},
     *  mimeTypesMessage="validators.mimeTypes",
     *  maxSize="2M",
     *  maxSizeMessage="validators.maxSize"
     * )
     * @Vich\UploadableField(mapping="articles", fileNameProperty="imageName", size="imageSize")
     * @var File|null
     */
    private $imageFile;

    /**
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "validators.length.max"
     * )
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    private $imageSize;

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @Assert\Length(
     *      max = 65535,
     *      maxMessage = "validators.length.max"
     * )
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Template::class, inversedBy="articleTemplate")
     * @Assert\Valid
     */
    private $template;

    /**
     * @ORM\OneToOne(targetEntity=Page::class, mappedBy="articleTemplate", cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $page;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param File|UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }
    
    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        // set (or unset) the owning side of the relation if necessary
        $newArticleTemplate = null === $page ? null : $this;
        if ($page->getArticleTemplate() !== $newArticleTemplate) {
            $page->setArticleTemplate($newArticleTemplate);
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
}
