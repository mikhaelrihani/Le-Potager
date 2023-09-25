<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait SessionLoginService
{
    public function sessionLogin(KernelBrowser $client, User $user)
    {

        // on cree une session pour simuler un utilisateur connecté
        $session = $client->getContainer()->get("session");
        // on crée un objet UsernamePasswordToken qui représente l'authentification de l'utilisateur.
        $token = new UsernamePasswordToken($user, "main", $user->getRoles());
        $session->set("_security_main", serialize($token));
        $session->save();

        //on defini un cookie pour simuler l'authentification de l'utilisateur.
        $cookie = new Cookie($session->getName(), $session->getId());

        // le client recupere le cookie 
        $client->getCookieJar()->set($cookie);
    }

    public function sessionLoginJWT(KernelBrowser $client)
    {
        // on cree une session pour simuler un utilisateur connecté
        $session = $client->getContainer()->get("session");
        $JWT = $this->getJWT($client, 'admin', 'admin');
        $session->set("_security_main", serialize($JWT));
        $session->save();

        //on defini un cookie pour simuler l'authentification de l'utilisateur.
        $cookie = new Cookie($session->getName(), $session->getId());

        // le client recupere le cookie 
        $client->getCookieJar()->set($cookie);
    }

    protected function getJWT(KernelBrowser $client, $username, $password)
    {

        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => $username,
                'password' => $password,
            ])
        );
     
        $data = json_decode($client->getResponse()->getContent());

        $JWT = $data->token;
        
        return $JWT;
    }



}