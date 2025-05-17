<?php


namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\User;
use App\Form\SignupFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request, SessionInterface $session, Connection $connection): Response
    {
        $errorMessage = null;

        if ($request->isMethod('POST')) {
            $email = trim($request->request->get('email', ''));
            $password = trim($request->request->get('password', ''));
            $remember = $request->request->get('remember');

            $sql = 'SELECT * FROM users WHERE email = :email LIMIT 1';
            $stmt = $connection->prepare($sql);
            $user = $stmt->executeQuery(['email' => $email])->fetchAssociative();

            if ($user && password_verify($password, $user['password'])) {
                $session->set('user', $user);

                $response = $this->redirectToRoute('home');

                if ($remember) {
                    $cookieValue = base64_encode($email . '|' . $password);
                    $cookie = Cookie::create('remember_me', $cookieValue, time() + 3600 * 24 * 3, '/');
                    $response->headers->setCookie($cookie);
                }

                return $response;
            } else {
                $errorMessage = 'Email ou mot de passe invalide.';
            }
        }

        return $this->render('security/login.html.twig', [
            'errorMessage' => $errorMessage,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        throw new \Exception('This should never be reached!');
    }


    #[Route('/signup', name: 'app_signup')]
    public function signup(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(SignupFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hasher le mot de passe
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            $user->setPassword($hashedPassword);

            
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Inscription réussie ! Vous pouvez vous connecter.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/signup.html.twig', [
            'signupForm' => $form->createView(),
        ]);
    }


   
}

