<?php

namespace App\Entity;

use App\Repository\NavRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=NavRepository::class)
 */
class Nav
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
    private $keyname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $main;

    /**
     * @ORM\OneToMany(targetEntity=NavLink::class, mappedBy="nav", orphanRemoval=true)
     */
    private $navLink;

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
        $this->navLink = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKeyname(): ?string
    {
        return $this->keyname;
    }

    public function setKeyname(?string $keyname): self
    {
        $this->keyname = $keyname;

        return $this;
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

    public function getMain(): ?bool
    {
        return $this->main;
    }

    public function setMain(bool $main): self
    {
        $this->main = $main;

        return $this;
    }

    /**
     * @return Collection|NavLink[]
     */
    public function getNavLink(): Collection
    {
        return $this->navLink;
    }

    public function addNavLink(NavLink $navLink): self
    {
        if (!$this->navLink->contains($navLink)) {
            $this->navLink[] = $navLink;
            $navLink->setNav($this);
        }

        return $this;
    }

    public function removeNavLink(NavLink $navLink): self
    {
        if ($this->navLink->contains($navLink)) {
            $this->navLink->removeElement($navLink);
            // set the owning side to null (unless already changed)
            if ($navLink->getNav() === $this) {
                $navLink->setNav(null);
            }
        }

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
}
