<?php

namespace App\Tests\Controller\Back;

use App\Tests\createAuthenticatedClient;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    // php bin/phpunit tests/Controller/Back/SecurityControllerTest.php --testdox
    use createAuthenticatedClient;
    public function testDisplayLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Connectez vous');
    }

    public function testLoginWithBadCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton("Se connecter")->form([
            "_username" => "admin",
            "_password" => "fakepassword"
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('http://localhost/login');

        $client->followRedirect();
        $this->assertSelectorTextContains('div.container > div:first-of-type', "Identifiants invalides.");

    }

    public function testSuccessfulLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton("Se connecter")->form([
            "_username" => "admin",
            "_password" => "admin"
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('http://localhost/admin');

        $client->followRedirect();
        $this->assertSelectorTextContains('h2', "Derniers utilisateurs");

    }
   
}