<?php
namespace App\Tests\Controller\Api\StatutCode;

use App\Tests\createAuthenticatedClient;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PUTTest extends WebTestCase
{
    //php bin/phpunit tests/Controller/Api/StatutCode/PUTTest.php --testdox

    use createAuthenticatedClient;
    private function testRequestResponse($routeName, $jsonData)
    {
       require __DIR__ . '/../../../dataTest.php';
       
       $client = $this->createAuthenticatedClient();
       $router = $client->getContainer()->get('router');

        $client->request(
            'PUT',
            $router->generate($routeName, ["id" => $id]),
            [],
            [],
            [],
            $jsonData
        );

        $response = $client->getResponse();

        $this->assertNotEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode(), 'La route ' . $routeName . ' génère une erreur 401 Unauthorized.');
        $this->assertFalse($response->isServerError(), 'La route ' . $routeName . ' génère une exception Symfony (500 Internal Server Error).');
        $this->assertJson($response->getContent(), 'La réponse de la route ' . $routeName . ' n\'est pas au format JSON valide.');
    }
    public function testPutGardenById(): void
    {
       require __DIR__ . '/../../../dataTest.php';

        $routeName = "app_api_garden_putGardenById";

        $this->testRequestResponse($routeName, $jsonDataPostPutGarden);
    }

    public function testPutUser(): void
    {
       require __DIR__ . '/../../../dataTest.php';

        $routeName = "app_api_user_putUser";

        $this->testRequestResponse($routeName, $jsonDataPostPutUser);
    }

}