<?php

namespace App\Tests\Controller\Back;

use App\Repository\UserRepository;
use App\Tests\SessionLoginService;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class UserControllerTest extends WebTestCase
{
    use SessionLoginService;

    public function testAdminPageIsRestricted(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');
        $client->getResponse()->getStatusCode();
        //cela m envoie une 302 car en cas de mauvaise auth je suis redirige vers login
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

    }
    public function testAccessToBackOfficeIfAdmin(): void
    {
        $client = static::createClient();

        // injection de dependance pour recuperer un utilisateur avec role admin
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findBy(["username" => "admin"])[ 0 ];
        $this->sessionLogin($client, $user);

        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

    }

    public function testNoAccessToBackOfficeIfUser(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findBy(["username" => "user"])[ 0 ];
        $this->sessionLogin($client, $user);

        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

    }
}