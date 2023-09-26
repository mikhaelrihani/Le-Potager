<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Controller404Test extends WebTestCase
{
    //php bin/phpunit tests/Controller/Controller404Test.php --testdox

    public function testAllRoutes404(): void
    {
        require __DIR__ . '/../dataTest.php';

        $client = static::createClient();
        $router = $client->getContainer()->get('router');

        foreach ($routes as $index) {
            foreach ($index as $routeNameArray) {
                foreach ($routeNameArray as $method => $routeName) {

                    $routeUrl = $router->generate($routeName, ['city' => $city, 'dist' => $dist, "id" => $id]);
                    $client->request($method, $routeUrl);
                    $statusCode = $client->getResponse()->getStatusCode();
                    $this->assertNotSame(404, $statusCode);

                }
            }
        }
    }
}