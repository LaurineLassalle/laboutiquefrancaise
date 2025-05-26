<?php

namespace App\Controller;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }
    #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
    public function password(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $actualPwd = $user->getPassword();
        //creation du formulaire
        $form = $this->createForm(PasswordUserType::class,$user, [
            'passwordHasher' => $passwordHasher
        ]);
        // On écoute la requete de l'utilisateur
        $form->handleRequest($request);
        //si le formulaire est soumis et qu'il est valide
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Votre mot de passe a bien été mis à jour'
            );
        }
        return $this->render('account/password.html.twig', [
            'modifyPwd' => $form->createView()

        ]);
    }
}
