<?php declare(strict_types=1);

namespace App;

use App\Models\Episode;
use App\Models\Page;
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
                $pages = $characters->info;

                $collected[] = new Character(
                    $person->id,
                    $person->name,
                    $person->status,
                    $person->species,
                    $person->origin->url,
                    $person->location->name,
                    $person->episode[0],
                    new Episode($episode->name),
                    $person->image,
                    new Page($pages->prev, $pages->next)
                );
            }
            return $collected;
        } catch (GuzzleException $e) {
            return [];
        }
    }

    public function searchFor(
        string $name,
        string $status,
        string $species
    ): array
    {
        try {
            $collected = [];
            if (!Cache::has($name)) {
                $query = "?name=$name&status=$status&species=$species";
                $response = $this->client->get($this->url . $query);
                $responseJson = $response->getBody()->getContents();
                Cache::save($name, $responseJson);
            } else {
                $responseJson = Cache::get($name);
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
                $pages = $characters->info;

                $collected[] = new Character(
                    $person->id,
                    $person->name,
                    $person->status,
                    $person->species,
                    $person->origin->url,
                    $person->location->name,
                    $person->episode[0],
                    new Episode($episode->name),
                    $person->image,
                    new Page($pages->next, $pages->prev)
                );
            }
            return $collected;
        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function pageChanger(string $change): array
    {
        try {
            $collected = [];
            $client = $this->client->get($this->url . "?page=$change");
            $characters = json_decode($client->getBody()->getContents());
            $pages = $characters->info;

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
                    $person->image,
                    new Page($pages->prev, $pages->next)
                );
            }
            return $collected;
        } catch (GuzzleException $exception) {
            return [];
        }
    }
}