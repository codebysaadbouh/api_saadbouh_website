<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            openapiContext: [
                'summary' => 'Get a category',
                'description' => 'Récupération d\'une catégorie en fonction de son ID',
            ]),
        new GetCollection(
            openapiContext: [
                'summary' => 'Get all categories',
                'description' => 'Récupération de toutes les catégories',
            ]),
        new Post(
            openapiContext: [
                'summary' => 'Create a category',
                'description' => 'Création d\'une catégorie',
            ]),
        new Put(
            openapiContext: [
                'summary' => 'Update a category',
                'description' => 'Mise à jour d\'une catégorie',
            ]),
        new Delete(
            openapiContext: [
                'summary' => 'Delete a category',
                'description' => 'Suppression d\'une catégorie en fonction de son ID',
            ]),
    ],
    normalizationContext: ['groups' => ['category_read']],
    denormalizationContext: ['groups' => ['category_write']],
    order: ['title' => 'ASC']
)]
/*
#[ApiResource(
    uriTemplate: '/categories/{id}/articles',
    operations: [new Get()],
    uriVariables: [
        'id' => new Link (
            fromProperty: 'category',
            fromClass: Article::class,
        )
    ],
    normalizationContext: ['groups' => ['category_read']],
    paginationEnabled: true,
    paginationItemsPerPage: 15
)]
*/
#[ApiFilter(SearchFilter::class, properties:['title'=> 'partial'])]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['category_read', 'article_read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['category_read', 'article_read', 'category_write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['category_read', 'article_read', 'category_write'])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Article::class)]
    #[Groups(['category_read'])]
    private Collection $article;

    public function __construct()
    {
        $this->article = new ArrayCollection();
    }

    #[Groups(['category_read'])]
    public function getArticleNumbers(): int
    {
        return $this->article->count();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->article->contains($article)) {
            $this->article->add($article);
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->article->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

        return $this;
    }
}
