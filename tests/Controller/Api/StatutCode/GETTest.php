<?php
namespace App\Tests\Controller\Api\StatutCode;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GETTest extends WebTestCase
{
    //php bin/phpunit tests/Controller/Api/StatutCode/GETTest.php --testdox
    public function testAllRouteGet(): void
    {

        require __DIR__ . '/../../../dataTest.php';
        $method = 'GET';
        $client = $this->createClient();
        $router = $client->getContainer()->get('router');

        foreach ($routes["routesApi"][$method] as $routeName) {

            $client->request($method, $router->generate($routeName, ['city' => $city, 'dist' => $dist, "id" => $id], ));
            $response = $client->getResponse();
            $this->assertFalse($response->isServerError(), 'La route ' . $routeName . ' génère une exception Symfony (500 Internal Server Error).');
            $this->assertJson($response->getContent(), 'La réponse de la route ' . $routeName . ' n\'est pas au format JSON valide.');
        }
    }
}