<?php declare(strict_types=1);

namespace App;

use App\Models\Episode;
use GuzzleHttp\Client;
use App\Models\Character;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient
{
    private Client $client;
    private string $url = "https://rickandmortyapi.com/api/character/";

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchCharacters(): array
    {

        try {
            $collected = [];
            $response = $this->client->get($this->url . $this->selectCharacters());
            $characters = json_decode($response->getBody()->getContents());

            foreach ($characters as $person) {
                $episode = json_decode($this->client->get($person->episode[0])->getBody()->getContents());
                $collected[] = new Character(
                    $person->id,
                    $person->name,
                    $person->status,
                    $person->species,
                    $person->origin->url,
                    $person->location->name,
                    $person->episode[0],
                    new Episode($episode->name),
                    $person->image
                );
            }
            return $collected;
        } catch (GuzzleException $e) {
            return [];
        }

    }

    private function selectCharacters(): string
    {

        try {
            $collected = [];
            $client = $this->client->get($this->url);
            $response = json_decode($client->getBody()->getContents());

            $start = 0;
            $end = 6;
            for ($i = $start; $i < $end; $i++) {
                $collected[] = rand($response->results[0]->id, $response->info->count);
            }
            return implode(",", $collected);
        } catch (GuzzleException $exception) {
            return "1,2,3,4,5,6";
        }
    }
}