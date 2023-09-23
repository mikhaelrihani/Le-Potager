<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class UnsplashApiService
{

    private $client;
    private $apiKey;

    public function __construct(HttpClientInterface $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    /**
     * Return photos random from unsplash
     *
     * @param string $search search related picture to parameter
     * @return string Url picture
     */
    public function fetchPhotosRandom(string $search)
    {

        $response = $this->client->request(
            'GET',
            'https://api.unsplash.com/photos/random',
            [
                "query" => [
                    "client_id" => $this->apiKey,
                    "query"     => $search


                ]
            ]
        );

        $photoUrl = $response->toArray()[ 'urls' ][ 'regular' ];
        
        return $photoUrl;

    }

}