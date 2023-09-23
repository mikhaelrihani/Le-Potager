<?php
namespace App\Tests\Controller\Api\StatutCode;

use App\Tests\createAuthenticatedClient;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class POSTTest extends WebTestCase
{
    //php bin/phpunit tests/Controller/Api/StatutCode/POSTTest.php --testdox

    use createAuthenticatedClient;


    private function testRequestResponse($routeName, $jsonData)
    {
        require __DIR__ . '/../../../dataTest.php';

        $client = $this->createAuthenticatedClient();
        $router = $client->getContainer()->get('router');

        $client->request(
            'POST',
            $router->generate($routeName, ["gardenId" => $gardenId, "id" => $id]),
            [],
            [],
            [],
            $jsonData
        );

        $response = $client->getResponse();

        $this->assertNotEquals(Response::HTTP_UNAUTHORIZED, 'La route ' . $routeName . ' génère une erreur 401 Unauthorized.');
        $this->assertFalse($response->isServerError(), 'La route ' . $routeName . ' génère une exception Symfony (500 Internal Server Error).');
        $this->assertJson($response->getContent(), 'La réponse de la route ' . $routeName . ' n\'est pas au format JSON valide.');
    }

    public function testPostGarden(): void
    {
        require __DIR__ . '/../../../dataTest.php';

        $routeName = "app_api_garden_postGarden";

        $this->testRequestResponse($routeName, $jsonDataPostPutGarden);
    }

    public function testPostUser(): void
    {
        require __DIR__ . '/../../../dataTest.php';

        $routeName = "app_api_user_postUsers";

        $this->testRequestResponse($routeName, $jsonDataPostPutUser);
    }

    public function testAddPictureToRegisteredGarden(): void
    {
        require __DIR__ . '/../../../dataTest.php';

        $routeName = "app_api_garden_addPictureToRegisteredGarden";

        $this->testRequestResponse($routeName, $jsonDataPostImage);
    }

    public function testPostFavoriteUser(): void
    {
        require __DIR__ . '/../../../dataTest.php';

        $routeName = "app_api_user_postFavoriteUser";

        $this->testRequestResponse($routeName, $jsonDataPostPutGarden);
    }

}