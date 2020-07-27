<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\SubNavRepository")
 */
class SubNav
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=NavLink::class, inversedBy="subNavs")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $parent;

    /**
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity=NavLink::class, mappedBy="subNav", orphanRemoval=true)
     */
    private $navLinks;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

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
        $this->navLinks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?NavLink
    {
        return $this->parent;
    }

    public function setParent(?NavLink $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|NavLink[]
     */
    public function getNavlinks(): Collection
    {
        return $this->navLinks;
    }

    public function addNavlink(NavLink $navLink): self
    {
        if (!$this->navLinks->contains($navLink)) {
            $this->navLinks[] = $navLink;
            $navLink->setSubNav($this);
        }

        return $this;
    }

    public function removeNavlink(NavLink $navLink): self
    {
        if ($this->navLinks->contains($navLink)) {
            $this->navLinks->removeElement($navLink);
            // set the owning side to null (unless already changed)
            if ($navLink->getSubNav() === $this) {
                $navLink->setSubNav(null);
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
}
