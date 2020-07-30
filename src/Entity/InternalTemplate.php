<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InternalTemplateRepository")
 */
class InternalTemplate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @Assert\Length(
     *      max = 65535,
     *      maxMessage = "validators.length.max"
     * )
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Asset", mappedBy="internalTemplate")
     */
    private $assets;

    /**
     * @ORM\ManyToOne(targetEntity=Template::class, inversedBy="internalTemplate")
     * @Assert\Valid
     */
    private $template;

    /**
     * @ORM\OneToOne(targetEntity=Page::class, mappedBy="internalTemplate", cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $page;

    public function __construct()
    {
        $this->assets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $asset->setInternalTemplate($this);
        }

        return $this;
    }

    public function removeAsset(Asset $asset): self
    {
        if ($this->assets->contains($asset)) {
            $this->assets->removeElement($asset);
            // set the owning side to null (unless already changed)
            if ($asset->getInternalTemplate() === $this) {
                $asset->setInternalTemplate(null);
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

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        // set (or unset) the owning side of the relation if necessary
        $newInternalTemplate = null === $page ? null : $this;
        if ($page->getInternalTemplate() !== $newInternalTemplate) {
            $page->setInternalTemplate($newInternalTemplate);
        }

        return $this;
    }
}
