<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\Favorite;
use App\Repository\FavoriteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/admin/users', name: 'admin_users')]
    public function adminUsers(UserRepository $userRepository, FavoriteRepository $favoriteRepository): Response
    {
        $users = $userRepository->findAll();

        $favoritesByUser = [];

        foreach ($users as $user) {
            $favorites = $favoriteRepository->findBy(['user' => $user]);
            $favoritesByUser[$user->getId()] = $favorites;
        }

        return $this->render('admin/admin_users.html.twig', [
            'users' => $users,
            'favorites' => $favoritesByUser
        ]);
    }

    #[Route('/manage/articles', name: 'admin_articles')]
    public function adminArticles(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();
        $articles = $em->getRepository(Article::class)->findAll(); 

        $favorites = [];
        foreach ($users as $user) {
            $favorites[$user->getId()] = $user->getFavorites();
        }

        return $this->render('/admin/admin_articles.html.twig', [
            'users' => $users,
            'favorites' => $favorites,
            'articles' => $articles, 
        ]);
    }
    #[Route('/delete-user', name: 'delete_user', methods: ['POST'])]
    public function deleteUser(Request $request, EntityManagerInterface $em): RedirectResponse
    {
        $userId = $request->request->get('user_id');

        if (!$userId || !is_numeric($userId)) {
            $this->addFlash('error', 'Invalid user ID.');
            return $this->redirectToRoute('admin_users');
        }

        $user = $em->getRepository(User::class)->find($userId);

        if (!$user) {
            $this->addFlash('error', 'User not found.');
            return $this->redirectToRoute('admin_users');
        }

        // Si tu as configuré cascade:remove, ceci n’est plus obligatoire :
        foreach ($user->getFavorites() as $fav) {
            $em->remove($fav);
        }

        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'User deleted successfully.');
        return $this->redirectToRoute('admin_users');
    }


    #[Route('/article/edit/{id}', name: 'edit_article')]
    public function editArticle(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $article = $em->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        if ($request->isMethod('POST')) {
            $article->setTitleArt($request->request->get('title_art'));
            $article->setContentArt($request->request->get('content_art'));
            $article->setDateArt(new \DateTime($request->request->get('date_art')));

            /** @var UploadedFile $imageFile */
            $imageFile = $request->files->get('image_art');
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = uniqid() . '_' . $safeFilename . '.' . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('upload_directory'), // define this param in config/services.yaml
                    $newFilename
                );

                $article->setImageArt($newFilename);
            }

            $em->flush();
            return $this->redirectToRoute('admin_articles');
        }

        return $this->render('admin/edit_article.html.twig', [
            'article' => $article,
        ]);
    }


    #[Route('/article/delete/{id}', name: 'delete_article', methods: ['POST'])]
public function deleteArticle(int $id, EntityManagerInterface $em): RedirectResponse
{
    $article = $em->getRepository(Article::class)->find($id);

    if ($article) {
        $image = $article->getImageArt();
        if ($image) {
            $imagePath = $this->getParameter('upload_directory') . '/' . $image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $em->remove($article);
        $em->flush();
    }

    return $this->redirectToRoute('admin_articles');
}

}
