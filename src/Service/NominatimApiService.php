<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class NominatimApiService
{

    private $client;


    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;

    }

    /**
     * Return coordinates from nominatim Api
     */
    public function getCoordinates($city, $adress = null)
    {

        $response = $this->client->request(
            'GET',
            'https://nominatim.openstreetmap.org/search',
            [
                "query" => [
                    "q" => $adress . $city,
                    "format" => "jsonv2"
                ]
            ]
        );
        
        $cityAllCoordinates = $response->toArray();

        if (!$cityAllCoordinates){
            $response = $this->client->request(
                'GET',
                'https://nominatim.openstreetmap.org/search',
                [
                    "query" => [
                        "q" => $city,
                        "format" => "jsonv2"
                    ]
                ]
            );

            $cityAllCoordinates = $response->toArray();
            
            if(!$cityAllCoordinates){
                return false;
            }
        } 

        $cityCoordinates = [];
        $cityCoordinates[ "lat" ] = $cityAllCoordinates[ 0 ][ "lat" ];
        $cityCoordinates[ "lon" ] = $cityAllCoordinates[ 0 ][ "lon" ];

        return $cityCoordinates;

    }

}