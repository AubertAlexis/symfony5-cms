<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\HomePageRepository")
 * @Vich\Uploadable()
 */
class HomePage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $aboutTitle;

    /**
     * @Vich\UploadableField(mapping="homePage", fileNameProperty="aboutImageName", size="aboutImageSize")
     * @var File|null
     */
    private $aboutImageFile;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    private $aboutImageSize;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $aboutImageName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $aboutText;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToOne(targetEntity=Seo::class, inversedBy="homePage", cascade={"persist", "remove"})
     */
    private $seo;

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAboutTitle(): ?string
    {
        return $this->aboutTitle;
    }

    public function setAboutTitle(?string $aboutTitle): self
    {
        $this->aboutTitle = $aboutTitle;

        return $this;
    }

    /**
     * @param File|UploadedFile|null $aboutImageFile
     */
    public function setAboutImageFile(?File $aboutImageFile = null): void
    {
        $this->aboutImageFile = $aboutImageFile;

        if (null !== $aboutImageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getAboutImageFile(): ?File
    {
        return $this->aboutImageFile;
    }

    public function setAboutImageName(?string $aboutImageName): void
    {
        $this->aboutImageName = $aboutImageName;
    }

    public function getAboutImageName(): ?string
    {
        return $this->aboutImageName;
    }
    
    public function setAboutImageSize(?int $aboutImageSize): void
    {
        $this->aboutImageSize = $aboutImageSize;
    }

    public function getAboutImageSize(): ?int
    {
        return $this->aboutImageSize;
    }

    public function getAboutText(): ?string
    {
        return $this->aboutText;
    }

    public function setAboutText(?string $aboutText): self
    {
        $this->aboutText = $aboutText;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getSeo(): ?Seo
    {
        return $this->seo;
    }

    public function setSeo(?Seo $seo): self
    {
        $this->seo = $seo;

        return $this;
    }
}
