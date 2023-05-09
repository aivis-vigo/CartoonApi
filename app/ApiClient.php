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

            if (!Cache::has('characters')) {
                $response = $this->client->get($this->url);
                $responseJson = $response->getBody()->getContents();
                Cache::save('characters', $responseJson);
            } else {
                $responseJson = Cache::get('characters');
            }

            $characters = json_decode($responseJson);

            foreach ($characters->results as $person) {
                $firstEpisodeUrl = $person->episode[0];
                $episodeCacheKey = md5($firstEpisodeUrl);
                if (!Cache::has($episodeCacheKey)) {
                    $episodeJson = $this->client->get($firstEpisodeUrl)->getBody()->getContents();
                    Cache::save($episodeCacheKey, $episodeJson);
                } else {
                    $episodeJson = Cache::get($episodeCacheKey);
                }

                $episode = json_decode($episodeJson);
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

    public function searchFor(string $name): array
    {
        try {
            $collected = [];
            $client = $this->client->get($this->url . "?name=$name");
            $characters = json_decode($client->getBody()->getContents());

            foreach ($characters->results as $person) {
                $firstEpisodeUrl = $person->episode[0];
                $episodeJson = $this->client->get($firstEpisodeUrl)->getBody()->getContents();
                $episode = json_decode($episodeJson);

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
        } catch (GuzzleException $exception) {
            return [];
        }
    }
}