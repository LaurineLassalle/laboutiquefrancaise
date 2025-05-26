<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        /*
         *
         */

        $client = static::createClient();//créer un faux navigateur
        $crawler = $client->request('GET', '/inscription');//le client va sur la page inscription
        //rempli les champs du formulaire
        $client->submitForm('Valider',[
            'register_user[email]' => 'julie@exemple.fr',
            'register_user[plainPassword][first]' => '123456',
            'register_user[plainPassword][second]' => '123456',
            'register_user[firstname]'=> 'Julie',
            'register_user[lastname]'=> 'Doe'
        ]);
        // Suis les redirection
        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        //on vérifie si l'inscription s'est bien passée
        $this->assertSelectorExists('div:contains("Votre compte est correctement créé, veuillez vous connecter")');

/*        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');*/
    }


}
