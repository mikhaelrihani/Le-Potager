<?php
namespace App\Tests\Controller\Back;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ControllerBack_500_302Test extends WebTestCase
{
    public function testBack_500_302(): void
    {

        include("_dataBackTest.php");
        $client = $this->createClient();
        $router = $client->getContainer()->get('router');

        foreach ($routesGet as $routeName) {

            $method = 'GET';
            $client->request($method, $router->generate($routeName, ['city' => $city, 'dist' => $dist, "id" => $id], ));
            $response = $client->getResponse();
         $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
            $this->assertFalse($response->isServerError(), 'La route ' . $routeName . ' génère une exception Symfony (500 Internal Server Error).');
        }

        foreach ($routesPost as $routeName) {

            $method = 'POST';
            $client->request($method, $router->generate($routeName, ["id" => $id, "gardenId" => $gardenId]));
            $response = $client->getResponse();
            $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
            $this->assertFalse($response->isServerError(), 'La route ' . $routeName . ' génère une exception Symfony (500 Internal Server Error).');
        }
    }
}