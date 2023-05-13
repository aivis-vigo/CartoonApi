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
    private string $url = "https://rickandmortyapi.com/api/";

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchRandomCharacters(): array
    {
        try {
            $collected = [];

            if (!Cache::has('randomSelect')) {
                $response = $this->client->get($this->url . "character/" . $this->randomlySelected());
                $responseJson = $response->getBody()->getContents();
                Cache::save('randomSelect', $responseJson);
            } else {
                $responseJson = Cache::get('randomSelect');
            }

            $characters = json_decode($responseJson);

            foreach ($characters as $person) {
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
                $count = $characters->info->count;

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

    public function fetchCharacters(): array
    {
        try {
            $collected = [];

            if (!Cache::has('characters')) {
                $response = $this->client->get($this->url . "character/");
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
                $response = $this->client->get($this->url . "character/" . $query);
                $responseJson = $response->getBody()->getContents();
                Cache::save($name . "_" . $status . "_" . $species, $responseJson);
            } else {
                $responseJson = Cache::get($name . "_" . $status . "_" . $species);
            }

            $characters = json_decode($responseJson);
            $pages = $characters->info;

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
            $client = $this->client->get($this->url . "character/?page=$change");
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

    private function randomlySelected(): string
    {
        try {
            $characters = [];
            $start = 0;
            $end= 6;

            $data = $this->client->get($this->url . "character/");
            $allCharacters = json_decode($data->getBody()->getContents());

            $firstCharacterId = $allCharacters->results[0]->id;
            $lastCharacterId = $allCharacters->info->count;

            for ($i = $start; $i < $end; $i++) {
                $characters[] = rand($firstCharacterId, $lastCharacterId);
            }

            return implode(",", $characters);
        } catch (GuzzleException $e) {
            return "1,2,3,4,5,6";
        }
    }
}