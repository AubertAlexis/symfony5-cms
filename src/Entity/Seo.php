<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeoRepository")
 */
class Seo
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
    private $metaTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *      max = 360,
     *      maxMessage = "validators.length.max"
     * )
     */
    private $metaDescription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "validators.length.max"
     * )
     */
    private $metaKeywords;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $noIndex;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $noFollow;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hideOnSitemap;

    /**
     * @ORM\OneToOne(targetEntity=Page::class, mappedBy="seo", cascade={"persist", "remove"})
     */
    private $page;

    /**
     * @ORM\OneToOne(targetEntity=HomePage::class, mappedBy="seo", cascade={"persist", "remove"})
     */
    private $homePage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): self
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    public function setMetaKeywords(?string $metaKeywords): self
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    public function getNoIndex(): ?bool
    {
        return $this->noIndex;
    }

    public function setNoIndex(?bool $noIndex): self
    {
        $this->noIndex = $noIndex;

        return $this;
    }

    public function getNoFollow(): ?bool
    {
        return $this->noFollow;
    }

    public function setNoFollow(?bool $noFollow): self
    {
        $this->noFollow = $noFollow;

        return $this;
    }

    public function getHideOnSitemap(): ?bool
    {
        return $this->hideOnSitemap;
    }

    public function setHideOnSitemap(?bool $hideOnSitemap): self
    {
        $this->hideOnSitemap = $hideOnSitemap;

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
        $newSeo = null === $page ? null : $this;
        if ($page->getSeo() !== $newSeo) {
            $page->setSeo($newSeo);
        }

        return $this;
    }

    public function getHomePage(): ?HomePage
    {
        return $this->homePage;
    }

    public function setHomePage(?HomePage $homePage): self
    {
        $this->homePage = $homePage;

        // set (or unset) the owning side of the relation if necessary
        $newSeo = null === $homePage ? null : $this;
        if ($homePage->getSeo() !== $newSeo) {
            $homePage->setSeo($newSeo);
        }

        return $this;
    }
}
