<?php declare(strict_types=1);

namespace App;
use GuzzleHttp\Client;
use App\Models\Character;

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
        $collected = [];
        $response = $this->client->get($this->url . $this->selectCharacters());
        $characters = json_decode($response->getBody()->getContents());

        foreach ($characters as $person) {
            $collected[] = new Character(
                $person->id,
                $person->name,
                $person->status,
                $person->species,
                $person->origin->url,
                $person->location->name,
                $person->episode[0],
                json_decode($this->client->get($person->episode[0])->getBody()->getContents())->name,
                $person->image
            );

        }
        return $collected;
    }

    private function selectCharacters(): string
    {
        $collected = [];
        $client = $this->client->get("https://rickandmortyapi.com/api/character");
        $response = json_decode($client->getBody()->getContents());

        $start = 0;
        $end = 6;
        for ($i = $start; $i < $end; $i++) {
            $collected[] = rand($response->results[0]->id, $response->info->count);
        }
        return implode(",", $collected);
    }
}