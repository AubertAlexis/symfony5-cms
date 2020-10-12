<?php

namespace App\Entity;

use App\Validator\Constraints as CustomAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="keyname", message="validators.unique")
 * @ORM\Entity(repositoryClass="App\Repository\NavRepository")
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
     * @Assert\NotBlank(message="validators.notNull")
     * @Assert\Length(
     *      min = 3,
     *      max = 255,
     *      minMessage = "validators.length.min",
     *      maxMessage = "validators.length.max"
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keyname;

    /**
     * @Assert\NotBlank(message="validators.notNull")
     * @Assert\Length(
     *      min = 3,
     *      max = 255,
     *      minMessage = "validators.length.min",
     *      maxMessage = "validators.length.max"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @CustomAssert\UniqMain(message="validators.nav.main")
     * @ORM\Column(type="boolean")
     */
    private $main;

    /**
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity=NavLink::class, mappedBy="nav", orphanRemoval=true)
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
        $this->navLinks = new ArrayCollection();
        $this->keyname = Uuid::v4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKeyname(): ?string
    {
        return $this->keyname;
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
    public function getNavLinks(): Collection
    {
        return $this->navLinks;
    }

    public function addNavLink(NavLink $navLink): self
    {
        if (!$this->navLinks->contains($navLink)) {
            $this->navLinks[] = $navLink;
            $navLink->setNav($this);
        }

        return $this;
    }

    public function removeNavLink(NavLink $navLink): self
    {
        if ($this->navLinks->contains($navLink)) {
            $this->navLinks->removeElement($navLink);
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
