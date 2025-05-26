<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    #[Route('/connexion', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // Gérer les erreurs
        $error = $authenticationUtils->getLastAuthenticationError();
        // Dernier username (email) pour eviter à l utilisateur de retaper son mail si il echoue a se connecter
        $lastUserName = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
                'error' => $error,
                'last_username' => $lastUserName
        ]);
    }

    #[Route('/deconnexion', name: 'app_logout', methods: ['GET'])]
    public function logout(): never
    {
       throw new \Exception('Dont');
    }

}
/*#[Route('/connexion', name: 'app_login')]
    #[Route('/logout', name: 'app_logout, methods: ['GET']')]
    public function logout(): never {

}*/