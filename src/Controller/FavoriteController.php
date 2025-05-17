<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Favorite;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FavoriteController extends AbstractController
{
    #[Route('/favorites', name: 'favorites_list')]
    #[IsGranted('ROLE_USER')]
    public function list(FavoriteRepository $favoriteRepository): JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        $user = $this->getUser();
        $favorites = $favoriteRepository->findBy(['user' => $user]);

        return $this->render('favorite/list.html.twig', [
            'favorites' => $favorites,
        ]);
    }

    #[Route('/favorite/add/{id}', name: 'favorite_add', methods: ['GET'])]
    public function add(int $id, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['status' => 'error', 'message' => 'You must be connected!'], 401);
        }

        $article = $em->getRepository(Article::class)->find($id);

        if (!$article) {
            return new JsonResponse(['status' => 'error', 'message' => 'Article not found.'], 404);
        }

        $existing = $em->getRepository(Favorite::class)->findOneBy([
            'user' => $user,
            'article' => $article,
        ]);

        if ($existing) {
            return new JsonResponse(['status' => 'info', 'message' => 'Article already added to favorites!']);
        }

        $favorite = new Favorite();
        $favorite->setUser($user);
        $favorite->setArticle($article);
        $em->persist($favorite);
        $em->flush();

        return new JsonResponse(['status' => 'success', 'message' => 'Article added to favorites!']);
    }


    #[Route('/favorites/remove/{id}', name: 'app_favorites_remove', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function remove(Article $article, EntityManagerInterface $em, FavoriteRepository $favoriteRepository): RedirectResponse
    {
        $user = $this->getUser();
        $favorite = $favoriteRepository->findOneBy(['user' => $user, 'article' => $article]);

        $em->remove($favorite);
        $em->flush();

        return $this->redirectToRoute('favorites_list');
    }
}
