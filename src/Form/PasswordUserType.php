<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            -> add('actualPassword', PasswordType::class, [
                'label' => "Votre mot de passe actuel",

                'attr' => [
                    'placeholder' => "Indiquez votre mot de passe actuel "
                ],
                'mapped' => false,
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'Votre nouveau mot de passe',
                    'hash_property_path' => 'password',
                    'attr' => [
                        'placeholder' => 'Choisissez votre nouveau mot de passe'
                    ],
                    'toggle' => true
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmez votre nouveau mot de passe'
                    ]
                ],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Valider",
                'attr' => [
                    'class' => 'btn btn-success'
            ]])
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){

                $form = $event->getForm();
                //récupérer le mot de passe actuel saisie par l'utilisateur
                $actualPwd = $form->get('actualPassword')->getData();
                //récupérer le mot de passe actuel en BDD de l'utiisateur connecté
                $user = $form->getConfig()->getOptions()['data'];

                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];
                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $actualPwd
                );

                if(!$isValid){
                    $form->get('actualPassword')->addError(new FormError("Votre mot de passe actuel n'est pas conforme, veuillez vérifier votre saisie"));
                }

                //Si c'est différent alors renvoyer une erreur
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null
        ]);
    }
}
