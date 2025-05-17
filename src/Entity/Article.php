<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use App\Entity\Category;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\Table(name: "t_article")]
#[Broadcast]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_art", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $identArt = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $dateArt = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $readtimeArt = null;

    #[ORM\Column(type: "string", length: 128)]
    private string $titleArt;

    #[ORM\Column(type: "text")]
    private string $hookArt;

    #[ORM\Column(type: "string", length: 128)]
    private string $urlArt;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(name: "fk_category_art", referencedColumnName: "id_cat")]
    private ?Category $categoryArt = null;

    #[ORM\Column(type: "text")]
    private string $contentArt;

    #[ORM\Column(type: "string", length: 64, nullable: true)]
    private ?string $imageArt = null;

    // --- Getters and setters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    // identArt
    public function getIdentArt(): ?int
    {
        return $this->identArt;
    }

    public function setIdentArt(?int $identArt): self
    {
        $this->identArt = $identArt;
        return $this;
    }

    // dateArt
    public function getDateArt(): ?\DateTimeInterface
    {
        return $this->dateArt;
    }

    public function setDateArt(?\DateTimeInterface $dateArt): self
    {
        $this->dateArt = $dateArt;
        return $this;
    }

    // readtimeArt
    public function getReadtimeArt(): ?int
    {
        return $this->readtimeArt;
    }

    public function setReadtimeArt(?int $readtimeArt): self
    {
        $this->readtimeArt = $readtimeArt;
        return $this;
    }

    // titleArt
    public function getTitleArt(): ?string
    {
        return $this->titleArt;
    }

    public function setTitleArt(string $titleArt): self
    {
        $this->titleArt = $titleArt;
        return $this;
    }

    // hookArt
    public function getHookArt(): ?string
    {
        return $this->hookArt;
    }

    public function setHookArt(string $hookArt): self
    {
        $this->hookArt = $hookArt;
        return $this;
    }

    // urlArt
    public function getUrlArt(): ?string
    {
        return $this->urlArt;
    }

    public function setUrlArt(string $urlArt): self
    {
        $this->urlArt = $urlArt;
        return $this;
    }

    // categoryArt
    public function getCategoryArt(): ?Category
    {
        return $this->categoryArt;
    }

    public function setCategoryArt(?Category $categoryArt): self
    {
        $this->categoryArt = $categoryArt;
        return $this;
    }

    // contentArt
    public function getContentArt(): ?string
    {
        return $this->contentArt;
    }

    public function setContentArt(string $contentArt): self
    {
        $this->contentArt = $contentArt;
        return $this;
    }

    // imageArt
    public function getImageArt(): ?string
    {
        return $this->imageArt;
    }

    public function setImageArt(?string $imageArt): self
    {
        $this->imageArt = $imageArt;
        return $this;
    }
}
