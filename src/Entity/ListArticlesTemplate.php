<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ListArticlesTemplateRepository")
 */
class ListArticlesTemplate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Template::class, inversedBy="listArticlesTemplate")
     * @Assert\Valid
     */
    private $template;

    /**
     * @ORM\OneToOne(targetEntity=Page::class, mappedBy="listArticlesTemplate", cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $page;

    public function getId(): ?int
    {
        return $this->id;
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
