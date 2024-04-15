<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ProfileController extends AbstractController
{
    private $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->csrfTokenManager = $csrfTokenManager;
    }

    #[Route('/profil', name: 'app_profile')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $user = $this->getUser();
        
        return $this->render('profile/index.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/profils', name: 'app_profiles')]
    public function showAllProfiles(EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('profile/all.html.twig', [
            'users' => $users
        ]);
    }


    #[Route('/generate_avatar', name: 'app_generate_avatar', methods: ['POST'])]
    public function generateAvatar(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $seed = $data['seed'] ?? null;

        // Vérification du token inshallah
        $token = new CsrfToken('generate_avatar_token', $request->headers->get('X-CSRF-Token'));
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            return $this->json(['error' => 'Invalid CSRF token'], JsonResponse::HTTP_FORBIDDEN);
        }

        if ($this->getUser()) {
            $user = $this->getUser();
            $user->setAvatarUrl($seed);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->json(['message' => 'Avatar généré']);
        }
    }
}
