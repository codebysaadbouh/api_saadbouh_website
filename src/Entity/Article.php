<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ApiResource(
    paginationEnabled: true,
    paginationItemsPerPage: 15
)]
#[ApiResource(
    operations: [
        new Get(
            openapiContext: [
                'summary' => 'Get an article',
                'description' => 'Récupération d\'un article en fonction de son ID',
            ]),
        new Post(
            openapiContext: [
                'summary' => 'Create an article',
                'description' => 'Création d\'un article',
            ]),
        new GetCollection(
            openapiContext: [
                'summary' => 'Get all articles',
                'description' => 'Récupération de tous les articles',
            ]),
        new Put(
            openapiContext: [
                'summary' => 'Update an article',
                'description' => 'Mise à jour d\'un article',
            ]),
        new Delete(
            openapiContext: [
                'summary' => 'Delete an article',
                'description' => 'Suppression d\'un article en fonction de son ID',
            ]),
    ],
    normalizationContext: ['groups' => ['article_read']],
    denormalizationContext: ['groups' => ['article_write']],
    order: ['createdAt' => 'DESC']
)]
#[ApiResource(
    uriTemplate: '/categories/{categoryID}/articles/{id}',
    operations: [new Get(
        openapiContext: [
            'summary' => 'get all articles of a specific category',
            'description' => 'Récupération de tous les articles d\'une catégorie en fonction de son ID',
        ])],
    uriVariables: [
        'categoryID' => new Link(toProperty: 'category', fromClass: Category::class),
        'id' => new Link(fromClass: Article::class),
    ],
)]
#[ApiResource(
    uriTemplate: '/categories/{categoryID}/articles',
    operations: [new GetCollection(
        openapiContext: [
            'summary' => 'Create a category',
            'description' => 'Récupération d\'un article, d\'une catégorie en fonction de son ID (categoryID)',
    ])],
    uriVariables: [
        'categoryID' => new Link(toProperty: 'category', fromClass: Category::class),
    ]
)]
#[ApiFilter(SearchFilter::class, properties:['title'=> 'partial'])]
#[ApiFilter(DateFilter::class, properties: ['createdAt'])]
#[ApiFilter(OrderFilter::class, properties: ['title', 'updatedAt'], arguments: ['orderParameterName' => 'order'])]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['article_read', 'category_read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['article_read', 'category_read','article_write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['article_read','category_read', 'article_write'])]
    private ?string $intro = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['article_read','category_read', 'article_write'])]
    private ?string $content = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[Groups(['article_read'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[Gedmo\Timestampable(on: 'update')]
    #[Groups(['article_read'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    #[Groups(['article_read', 'article_write'])]
    private ?string $cover = null;

    #[ORM\ManyToOne(inversedBy: 'article')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['article_read', 'article_write'])]
    private ?Category $category = null;

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

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function setIntro(?string $intro): self
    {
        $this->intro = $intro;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
