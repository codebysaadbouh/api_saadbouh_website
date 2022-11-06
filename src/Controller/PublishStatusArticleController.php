<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PublishStatusArticleController extends AbstractController
{
    private EntityManagerInterface $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(Article $article, Request $request): Article
    {
        /**
         *{
         *   "name" : "cheikh"
         * }
         * $body = json_decode($request->getContent(), true);
         * var_dump($body);exit;
         */
        $article->setIsPublished(!$article->isIsPublished());
        $this->manager->persist($article);
        $this->manager->flush();
        return $article;
    }
}