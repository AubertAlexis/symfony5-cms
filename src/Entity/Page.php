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
    const FORBIDDEN_SLUG = [
        "admin",
        "/",
        "/admin"
    ];

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
     * @CustomAssert\UniqSlug(message="validators.page.slug", forbiddenMessage="validators.page.forbiddenSlug")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "validators.length.max"
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\OneToMany(targetEntity=NavLink::class, mappedBy="page")
     * @Assert\Valid
     */
    private $navLinks;

    /**
     * @ORM\ManyToOne(targetEntity=Template::class, inversedBy="pages")
     * @Assert\Valid
     */
    private $template;

    /**
     * @ORM\OneToOne(targetEntity=InternalTemplate::class, inversedBy="page", cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $internalTemplate;

    /**
     * @ORM\OneToOne(targetEntity=ArticleTemplate::class, inversedBy="page", cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $articleTemplate;

    /**
     * @ORM\OneToOne(targetEntity=ListArticlesTemplate::class, inversedBy="page", cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $listArticlesTemplate;

    /**
     * @ORM\OneToOne(targetEntity=Seo::class, inversedBy="page", cascade={"persist", "remove"})
     */
    private $seo;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
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

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getInternalTemplate(): ?InternalTemplate
    {
        return $this->internalTemplate;
    }

    public function setInternalTemplate(?InternalTemplate $internalTemplate): self
    {
        $this->internalTemplate = $internalTemplate;

        return $this;
    }

    public function getArticleTemplate(): ?ArticleTemplate
    {
        return $this->articleTemplate;
    }

    public function setArticleTemplate(?ArticleTemplate $articleTemplate): self
    {
        $this->articleTemplate = $articleTemplate;

        return $this;
    }

    public function getListArticlesTemplate(): ?ListArticlesTemplate
    {
        return $this->listArticlesTemplate;
    }

    public function setListArticlesTemplate(?ListArticlesTemplate $listArticlesTemplate): self
    {
        $this->listArticlesTemplate = $listArticlesTemplate;

        return $this;
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
