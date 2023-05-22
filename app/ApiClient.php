<?php declare(strict_types=1);

namespace App;

use App\Models\Location;
use App\Models\Episode;
use App\Models\FirstEpisode;
use App\Models\Page;
use GuzzleHttp\Client;
use App\Models\Character;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient
{
    private Client $client;
    private string $url = "https://rickandmortyapi.com/api";

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchRandomCharacters(): array
    {
        try {
            $collected = [];

            if (!Cache::has('randomSelect')) {
                $response = $this->client->get($this->url . "/character/" . $this->randomlySelected());
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

                $collected[] = new Character(
                    $person->id,
                    $person->name,
                    $person->status,
                    $person->species,
                    $person->origin->url,
                    $person->location->name,
                    $person->episode[0],
                    new FirstEpisode($episode->name),
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
                $response = $this->client->get($this->url . "/character/");
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
                    new FirstEpisode($episode->name),
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
                $response = $this->client->get($this->url . "/character/" . $query);
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
                    new FirstEpisode($episode->name),
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

            if (!Cache::has('page_' . $change)) {
                $client = $this->client->get($this->url . "/character/?page=$change");
                $responseJson = $client->getBody()->getContents();
                Cache::save('page_' . $change, $responseJson);
            } else {
                $responseJson = Cache::get('page_' . $change);
            }

            $characters = json_decode($responseJson);
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
                    new FirstEpisode($episode->name),
                    $person->image,
                    new Page($pages->prev, $pages->next)
                );
            }
            return $collected;
        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function locationsPageChanger(string $number): array
    {
        try {
            $collected = [];
            $client = $this->client->get($this->url . "/location/$number");
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
                    new FirstEpisode($episode->name),
                    $person->image,
                    new Page($pages->prev, $pages->next)
                );
            }
            return $collected;
        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function fetchEpisodes(): array
    {
        $collected = [];
        $client = $this->client->get($this->url . "/episode");
        $episodesJson = $client->getBody()->getContents();
        $episodes = json_decode($episodesJson);
        $pages = $episodes->info;

        foreach ($episodes->results as $episode) {
            $collected[] = new Episode(
                $episode->id,
                $episode->name,
                $episode->air_date,
                $episode->episode,
                $episode->characters,
                new Page($pages->prev, $pages->next)
            );
        }
        return $collected;
    }

    public function fetchLocations(): array
    {
        $collected = [];

        $client = $this->client->get($this->url . "/location");
        $locationsJson = $client->getBody()->getContents();
        $locations = json_decode($locationsJson);
        $pages = $locations->info;

        foreach ($locations->results as $location) {
            $collected[] = new Location(
                $location->id,
                $location->name,
                $location->type,
                $location->dimension,
                new Page($pages->prev, $pages->next)
            );
        }
        return $collected;
    }

    public function fetchEpisode(string $number): array
    {
        $collected = [];

        $client = $this->client->get($this->url . "/episode/$number");
        $episodesJson = $client->getBody()->getContents();
        $episodes = json_decode($episodesJson);

        $id = [];
        foreach ($episodes->characters as $character) {
            $id[] = basename($character);
        }
        $characterIds = implode(",", $id);

        $getCharacters = $this->client->get($this->url . "/character/$characterIds");
        $charactersJson = $getCharacters->getBody()->getContents();
        $characters = json_decode($charactersJson);
        $pages = $characters->info;

        foreach ($characters as $person) {
            $firstEpisode = $this->client->get($person->episode[0]);
            $firstEpisodeJson = $firstEpisode->getBody()->getContents();
            $episodeTitle = json_decode($firstEpisodeJson);

            $collected[] = new Character(
                $person->id,
                $person->name,
                $person->status,
                $person->species,
                $person->origin->url,
                $person->location->name,
                $person->episode[0],
                new FirstEpisode($episodeTitle->name),
                $person->image,
                new Page($pages->prev, $pages->next)
            );
        }
        return $collected;
    }

    public function locationResidents(string $number): array
    {
        $collected = [];

        $client = $this->client->get($this->url . "/location/$number");
        $locationJson = $client->getBody()->getContents();
        $location = json_decode($locationJson);

        $id = [];
        foreach ($location->residents as $character) {
            $id[] = basename($character);
        }
        $characterIds = implode(",", $id);

        $getCharacters = $this->client->get($this->url . "character/$characterIds");
        $charactersJson = $getCharacters->getBody()->getContents();
        $characters = json_decode($charactersJson);
        $pages = $characters->info;

        foreach ($characters as $person) {
            $firstEpisode = $this->client->get($person->episode[0]);
            $firstEpisodeJson = $firstEpisode->getBody()->getContents();
            $episodeTitle = json_decode($firstEpisodeJson);

            $collected[] = new Character(
                $person->id,
                $person->name,
                $person->status,
                $person->species,
                $person->origin->url,
                $person->location->name,
                $person->episode[0],
                new FirstEpisode($episodeTitle->name),
                $person->image,
                new Page($pages->prev, $pages->next)
            );
        }
        return $collected;
    }

    public function selectedEpisode(string $number): Episode
    {
        $client = $this->client->get($this->url . "/episode/$number");
        $episodesJson = $client->getBody()->getContents();
        $episodes = json_decode($episodesJson);

        return new Episode(
            $episodes->id,
            $episodes->name,
            $episodes->air_date,
            $episodes->episode,
            $episodes->characters,
            new Page("-", "-")
        );
    }

    public function selectedLocation(string $number): Location
    {
        $client = $this->client->get($this->url . "/location/$number");
        $locationJson = $client->getBody()->getContents();
        $location = json_decode($locationJson);

        return new Location(
            $location->id,
            $location->name,
            $location->type,
            $location->dimension,
            new Page("-", "-")
        );
    }

    private function randomlySelected(): string
    {
        try {
            $characters = [];
            $start = 0;
            $end = 6;

            $data = $this->client->get($this->url . "/character/");
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