<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;


class ArticleController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $latestArticles = $articleRepository->findLatestArticles();   // Implement this method in your repo
        $featuredArticles = $articleRepository->findFeaturedArticles(); // Implement this method in your repo

        return $this->render('article/index.html.twig', [
            'latestArticles' => $latestArticles,
            'featuredArticles' => $featuredArticles,
        ]);
    }

    #[Route('/article/{id}', name: 'article_show', requirements: ['id' => '\d+'])]
    public function show(int $id, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/articles', name: 'article_list')]
    public function list(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('article/list.html.twig', [
            'articles' => $articles,
        ]);
    }
     #[Route('/favorites/add', name: 'favorites_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        // Example: retrieve the article ID from POST
        $articleId = $request->request->get('article_id');

        if (!$articleId) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'No article ID provided',
            ]);
        }

        // Here, you would add your logic to save the favorite for the current user
        // For example:
        // $user = $this->getUser();
        // $favorite = new Favorite();
        // $favorite->setUser($user);
        // $favorite->setArticle($article);
        // $em->persist($favorite);
        // $em->flush();

        // For demo, return success:
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Article added to favorites!',
        ]);
    }

     #[Route('/search', name: 'search_article', methods: ['GET', 'POST'])]
    public function search(Request $request, Connection $connection): Response
    {
        $articles = [];

        if ($request->isMethod('POST')) {
            $min = $request->request->getInt('readingTimeMin', 0);
            $max = $request->request->getInt('readingTimeMax', PHP_INT_MAX);

            $sql = 'SELECT * FROM t_article WHERE readtime_art BETWEEN :min AND :max';
            $stmt = $connection->prepare($sql);
            $result = $stmt->executeQuery(['min' => $min, 'max' => $max]);
            $articles = $result->fetchAllAssociative();
        }

        return $this->render('article/search.html.twig', [
            'articles' => $articles
        ]);
    }
}


