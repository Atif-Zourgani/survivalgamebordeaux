<?php

namespace App\Controller;

use App\Entity\UserSession;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

class UserSessionController extends AbstractController
{
    #[Route('/inscription_session', name: 'app_user_session')]
    public function index(SessionRepository $sessionRepository): Response
    {
        $today = new \DateTime();
        $sessions = $sessionRepository->findSessionAfterDate($today);

        if ($this->getUser()->getSession()) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('user_session/index.html.twig', [
            'sessions' => $sessions
        ]);
    }

    #[Route('/registration_session/{idSession}', name: 'app_user_session_registration')]
    public function registrationSession(int $idSession, SessionRepository $sessionRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser()->getSession()) {
            return $this->redirectToRoute('app_user_ongoing_session', [
                'sessionName' => $user->getSession()->getName()
            ]);
        }

        $session = $sessionRepository->findOneBy(['id' => $idSession]);
        if (!$session) {
            return $this->redirectToRoute('app_home');
        }

        $user->setSession($session);
        $entityManagerInterface->persist($user);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('app_user_ongoing_session', [
            'sessionName' => $user->getSession()->getName()
        ]);
    }

    #[Route('session/{sessionName}', name: 'app_user_ongoing_session')]
    public function ongoingSession(string $sessionName, UserRepository $userRepository)
    {
        $user = $this->getUser();
        if (!$user || !$user->getSession()) {
            return $this->redirectToRoute('app_login');
        }

        $usersOngoingSession = $userRepository->findBy(['session' => $user->getSession()]);

        return $this->render('user_session/ongoingSession.html.twig',[
            'usersOngoingSession' => $usersOngoingSession,
        ]);
    }

    #[Route('quite_session', name:'app_quite_session', methods: ['POST'])]
    public function quiteSession(EntityManagerInterface $entityManagerInterface, Request $request, CsrfTokenManagerInterface $csrfTokenManagerInterface): Response
    {
        $submittedToken = $request->request->get('_csrf_token');

        if (!$csrfTokenManagerInterface->isTokenValid(new CsrfToken('generate_avatar_token', $submittedToken))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
            return $this->redirectToRoute('app_home');
        }

        $user = $this->getUser();
        $userSession = new UserSession;

        if ($user && $user->getSession()) {

            $userSession
            ->setUser($user)
            ->setSession($user->getSession())
            ->setRank(0);

            $user->removeSession();

            $entityManagerInterface->persist($user);
            $entityManagerInterface->persist($userSession);
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_profile');
        } else {
            return $this->redirectToRoute('app_home');
        }
    }
}
