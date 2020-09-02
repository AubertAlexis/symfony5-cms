<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TemplateRepository")
 */
class Template
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "validators.length.max"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "validators.length.max"
     * )
     */
    private $keyname;

    /**
     * @ORM\OneToMany(targetEntity=Page::class, mappedBy="template")
     */
    private $pages;

    /**
     * @ORM\OneToMany(targetEntity=InternalTemplate::class, mappedBy="template")
     */
    private $internalTemplate;

    /**
     * @ORM\OneToMany(targetEntity=ArticleTemplate::class, mappedBy="template")
     */
    private $articleTemplate;

    /**
     * @ORM\OneToMany(targetEntity=ListArticlesTemplate::class, mappedBy="template")
     */
    private $listArticlesTemplate;

    public function __construct()
    {
        $this->pages = new ArrayCollection();
        $this->articleTemplate = new ArrayCollection();
        $this->listArticlesTemplate = new ArrayCollection();
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

    public function getKeyname(): ?string
    {
        return $this->keyname;
    }

    public function setKeyname(string $keyname): self
    {
        $this->keyname = $keyname;

        return $this;
    }

    /**
     * @return Collection|Page[]
     */
    public function getPages(): Collection
    {
        return $this->pages;
    }

    public function addPage(Page $page): self
    {
        if (!$this->pages->contains($page)) {
            $this->pages[] = $page;
            $page->setTemplate($this);
        }

        return $this;
    }

    public function removePage(Page $page): self
    {
        if ($this->pages->contains($page)) {
            $this->pages->removeElement($page);
            // set the owning side to null (unless already changed)
            if ($page->getTemplate() === $this) {
                $page->setTemplate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|InternalTemplate[]
     */
    public function getInternalTemplate(): Collection
    {
        return $this->internalTemplate;
    }

    public function addInternalTemplate(InternalTemplate $internalTemplate): self
    {
        if (!$this->internalTemplate->contains($internalTemplate)) {
            $this->internalTemplate[] = $internalTemplate;
            $internalTemplate->setTemplate($this);
        }

        return $this;
    }

    public function removeInternalTemplate(InternalTemplate $internalTemplate): self
    {
        if ($this->internalTemplate->contains($internalTemplate)) {
            $this->internalTemplate->removeElement($internalTemplate);
            // set the owning side to null (unless already changed)
            if ($internalTemplate->getTemplate() === $this) {
                $internalTemplate->setTemplate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ArticleTemplate[]
     */
    public function getArticleTemplate(): Collection
    {
        return $this->articleTemplate;
    }

    public function addArticleTemplate(ArticleTemplate $articleTemplate): self
    {
        if (!$this->articleTemplate->contains($articleTemplate)) {
            $this->articleTemplate[] = $articleTemplate;
            $articleTemplate->setTemplate($this);
        }

        return $this;
    }

    public function removeArticleTemplate(ArticleTemplate $articleTemplate): self
    {
        if ($this->articleTemplate->contains($articleTemplate)) {
            $this->articleTemplate->removeElement($articleTemplate);
            // set the owning side to null (unless already changed)
            if ($articleTemplate->getTemplate() === $this) {
                $articleTemplate->setTemplate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ListArticlesTemplate[]
     */
    public function getListArticlesTemplate(): Collection
    {
        return $this->listArticlesTemplate;
    }

    public function addListArticlesTemplate(ListArticlesTemplate $listArticlesTemplate): self
    {
        if (!$this->listArticlesTemplate->contains($listArticlesTemplate)) {
            $this->listArticlesTemplate[] = $listArticlesTemplate;
            $listArticlesTemplate->setTemplate($this);
        }

        return $this;
    }

    public function removeListArticlesTemplate(ListArticlesTemplate $listArticlesTemplate): self
    {
        if ($this->listArticlesTemplate->contains($listArticlesTemplate)) {
            $this->listArticlesTemplate->removeElement($listArticlesTemplate);
            // set the owning side to null (unless already changed)
            if ($listArticlesTemplate->getTemplate() === $this) {
                $listArticlesTemplate->setTemplate(null);
            }
        }

        return $this;
    }
}
