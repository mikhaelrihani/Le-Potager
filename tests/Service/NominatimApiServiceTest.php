<?php


namespace App\Tests\Service;

use App\Service\NominatimApiService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class NominatimApiServiceTest extends TestCase
{
    //php bin/phpunit tests/Service/NominatimApiServiceTest.php --testdox
    public function testGetCoordinates()
    {

        // creation de mock  pour ne pas faire de vrai appel a l api (pas toujours fonctionelle)
        // Create a mock for the HttpClientInterface
        $httpClient = $this->createMock(HttpClientInterface::class);

        // Create a map of requests to responses
        $requestResponseMap = [
            [
                'GET',
                'https://nominatim.openstreetmap.org/search',
                [
                    'query' => ['q' => 'addresscity', 'format' => 'jsonv2'],
                ],
                $this->createResponse([["lat" => "48.8588443", "lon" => "2.2943506"]]),
            ],
            [
                'GET',
                'https://nominatim.openstreetmap.org/search',
                [
                    'query' => ['q' => 'city', 'format' => 'jsonv2'],
                ],
                $this->createResponse([["lat" => "48.8588443", "lon" => "2.2943506"]]),
            ],
        ];

        // Configure the HttpClient mock to use the map of responses
        $httpClient->method('request')->willReturnMap($requestResponseMap);

        // Create an instance of NominatimApiService with the mock HttpClientInterface
        $nominatimApiService = new NominatimApiService($httpClient);

        // Call the method you're testing with address and city
        $coordinates = $nominatimApiService->getCoordinates('city', 'address');

        // Assert that the coordinates are as expected
        $this->assertEquals(['lat' => '48.8588443', 'lon' => '2.2943506'], $coordinates);
    }

    // Helper method to create a mock ResponseInterface
    private function createResponse($data)
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('toArray')->willReturn($data);
        return $response;
    }

    public function testGetCoordinatesWithNullCity()
    {
        // Create a mock for the HttpClientInterface
        $httpClient = $this->createMock(HttpClientInterface::class);

        // Create an instance of NominatimApiService with the mock HttpClientInterface
        $nominatimApiService = new NominatimApiService($httpClient);

        // Call the method you're testing with a null city and any address
        $coordinates = $nominatimApiService->getCoordinates(null, 'address');

        // Assert that the coordinates are false
        $this->assertFalse($coordinates);
    }

}