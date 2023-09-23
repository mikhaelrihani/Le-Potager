<?php
namespace App\Tests\Controller\Api\StatutCode;

use App\Tests\createAuthenticatedClient;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DELETETest extends WebTestCase
{
    //php bin/phpunit tests/Controller/Api/StatutCode/DELETETest.php --testdox

    use createAuthenticatedClient;


    public function testRoutesDelete(): void
    {
        require __DIR__ . '/../../../dataTest.php';
        $method = 'DELETE';
        $client = $this->createAuthenticatedClient();
        $router = $client->getContainer()->get('router');

        foreach ($routes["routesApi"][$method] as $routeName) {

            $client->request($method, $router->generate($routeName, ["id" => $id, "gardenId" =>$gardenId ]));
            $response = $client->getResponse();

            $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
            $this->assertNotEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode(), 'La route ' . $routeName . ' génère une erreur 401 Unauthorized.');
            $this->assertFalse($response->isServerError(), 'La route ' . $routeName . ' génère une exception Symfony (500 Internal Server Error).');
            $this->assertJson($response->getContent(), 'La réponse de la route ' . $routeName . ' n\'est pas au format JSON valide.');
        }
    }
}