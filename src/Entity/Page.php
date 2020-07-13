<?php

namespace App\Entity;

use App\Validator\Constraints as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 */
class Page
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="validators.notNull")
     * @Assert\Length(
     *      min = 3,
     *      max = 255,
     *      minMessage = "validators.length.min",
     *      maxMessage = "validators.length.max"
     * )
     */
    private $title;

    /**
     * @CustomAssert\UniqSlug(message="validators.page.slug")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "validators.length.max"
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @Assert\Length(
     *      max = 65535,
     *      maxMessage = "validators.length.max"
     * )
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Asset", mappedBy="page")
     */
    private $assets;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\OneToMany(targetEntity=NavLink::class, mappedBy="page")
     */
    private $navLinks;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setSlugAndUpdatedAt()
    {
        if (empty($this->slug)) $this->setSlug((new Slugify())->slugify($this->title));
        
        $this->updatedAt = new \DateTime();
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->assets = new ArrayCollection();
        $this->navLinks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @return Collection|Asset[]
     */
    public function getAssets(): Collection
    {
        return $this->assets;
    }

    public function addAsset(Asset $asset): self
    {
        if (!$this->assets->contains($asset)) {
            $this->assets[] = $asset;
            $asset->setPage($this);
        }

        return $this;
    }

    public function removeAsset(Asset $asset): self
    {
        if ($this->assets->contains($asset)) {
            $this->assets->removeElement($asset);
            // set the owning side to null (unless already changed)
            if ($asset->getPage() === $this) {
                $asset->setPage(null);
            }
        }

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return Collection|NavLink[]
     */
    public function getNavLinks(): Collection
    {
        return $this->navLinks;
    }

    public function addNavLink(NavLink $navLink): self
    {
        if (!$this->navLinks->contains($navLink)) {
            $this->navLinks[] = $navLink;
            $navLink->setPage($this);
        }

        return $this;
    }

    public function removeNavLink(NavLink $navLink): self
    {
        if ($this->navLinks->contains($navLink)) {
            $this->navLinks->removeElement($navLink);
            // set the owning side to null (unless already changed)
            if ($navLink->getPage() === $this) {
                $navLink->setPage(null);
            }
        }

        return $this;
    }
}
