<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        //creation du formulaire
        $registerForm = $this->createForm(RegisterUserType::class, $user);
        // On écoute la requete de l'utilisateur
        $registerForm->handleRequest($request);

        //si le formulaire est soumis et qu'il est valide
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
           /* dd($registerForm->getData());/**/
            // tu enregistre en BDD
            $entityManager->persist($user);//obligatoire lors d'une création
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Votre compte est correctement créé, veuillez vous connecter'
            );
            return $this->redirectToRoute('app_login');
        }

        // tu envoies un message de confirmation de compte bien créé
        return $this->render('register/index.html.twig', [
            'registerForm' => $registerForm->createView(),
        ]);
    }
}
