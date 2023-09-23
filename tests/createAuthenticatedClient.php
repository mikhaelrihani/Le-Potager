<?php
namespace App\Tests;

trait createAuthenticatedClient
{
    use SessionLoginService;

    protected function createAuthenticatedClient()
    {
        $client = static::createClient();
        $this->sessionLoginJWT($client);
        $JWT = $this->getJWT($client, "admin", "admin");
        $client->setServerParameters([
            'HTTP_Authorization' => sprintf('Bearer %s', $JWT),
            'CONTENT_TYPE'       => 'application/json',
        ]);
        return $client;
    }
}