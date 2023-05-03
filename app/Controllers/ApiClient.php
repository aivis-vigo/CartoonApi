<?php declare(strict_types=1);

namespace App\Controllers;
use GuzzleHttp\Client;
use App\Models\Character;

class ApiClient
{
    private Client $client;
    private const URL = 'https://rickandmortyapi.com/api/character';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchCharacters(): array
    {
        $collected = [];
        $response = $this->client->get(self::URL);
        $otherResponse = json_decode($response->getBody()->getContents());

        foreach ($otherResponse->results as $person) {
            $collected[] = new Character(
                $person->name,
                $person->status,
                $person->species,
                $person->location->name,
                $person->origin->name,
                $person->image
            );
        }
        return $collected;
    }
}