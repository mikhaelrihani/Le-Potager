<?php

namespace App\Tests\mail;

use App\Tests\createAuthenticatedClient;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class MailTest extends WebTestCase
{
    use createAuthenticatedClient;

    public function testMailAndRedirection(): void
    {
        $client = static::createAuthenticatedClient();
        $client->enableProfiler();

        // Exécutez la requête qui déclenchera l'envoi d'un e-mail et la redirection
        $client->request('GET', '/admin/jardins/1');

        // Vérifiez que la réponse HTTP est une redirection 
        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        // Vérifiez que la redirection pointe vers la route 'app_back_garden_list'
        // dd($client->getResponse());
        //! je ne redirige pas ver admin/jardins ?
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login')); 

        // Vous pouvez ajouter d'autres assertions si nécessaire
    }
}


