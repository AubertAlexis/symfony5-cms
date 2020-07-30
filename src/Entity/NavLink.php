<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\NavLinkRepository")
 */
class NavLink
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "validators.length.max"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $internal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "validators.length.max"
     * )
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class, inversedBy="navLinks")
     */
    private $page;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Nav::class, inversedBy="navLinks")
     * @ORM\JoinColumn(nullable=true)
     */
    private $nav;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(message = "validators.positive")
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity=SubNav::class, mappedBy="parent", orphanRemoval=true)
     */
    private $subNavs;

    /**
     * @ORM\ManyToOne(targetEntity=SubNav::class, inversedBy="navLinks")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $subNav;

    /**
     * @Assert\Callback()
     *
     * @param ExecutionContextInterface $context
     */
    public function internalValidation(ExecutionContextInterface $context)
    {
        if ($this->getInternal() == '1') {

            if ($this->getPage() === null) {
                $context->buildViolation(_('validators.nav.callback.page'))
                    ->atPath('page')
                    ->addViolation();
            }

            if ($this->getTitle() === null && $this->getPage() !== null) {
                $pageTitle = $this->getPage()->getTitle();

                $this->setTitle($pageTitle);
            }

            $this->setLink(null);

        } else {
            if ($this->getTitle() === null) {
                $context->buildViolation(_('validators.nav.callback.title'))
                    ->atPath('title')
                    ->addViolation();
            }

            $this->setPage(null);
        }

    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->subNavs = new ArrayCollection();
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

    public function getInternal(): ?bool
    {
        return $this->internal;
    }

    public function setInternal(bool $internal): self
    {
        $this->internal = $internal;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

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

    public function getNav(): ?Nav
    {
        return $this->nav;
    }

    public function setNav(?Nav $nav): self
    {
        $this->nav = $nav;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection|SubNav[]
     */
    public function getSubNavs(): Collection
    {
        return $this->subNavs;
    }

    public function addSubNav(SubNav $subNav): self
    {
        if (!$this->subNavs->contains($subNav)) {
            $this->subNavs[] = $subNav;
            $subNav->setParent($this);
        }

        return $this;
    }

    public function removeSubNav(SubNav $subNav): self
    {
        if ($this->subNavs->contains($subNav)) {
            $this->subNavs->removeElement($subNav);
            // set the owning side to null (unless already changed)
            if ($subNav->getParent() === $this) {
                $subNav->setParent(null);
            }
        }

        return $this;
    }

    public function getSubNav(): ?SubNav
    {
        return $this->subNav;
    }

    public function setSubNav(?SubNav $subNav): self
    {
        $this->subNav = $subNav;

        return $this;
    }
}
