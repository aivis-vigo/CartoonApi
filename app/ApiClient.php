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
            if (!Cache::has('page_' . $number)) {
                $client = $this->client->get($this->url . "/location/$number");
                $responseJson = $client->getBody()->getContents();
                Cache::save('location_page_' . $number, $responseJson);
            } else {
                $responseJson = Cache::get('location_page_' . $number);
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

    public function fetchEpisodes(): array
    {
        $collected = [];
        if (!Cache::has('episodes')) {
            $client = $this->client->get($this->url . "/episode");
            $responseJson = $client->getBody()->getContents();
        } else {
            $responseJson = Cache::get('episodes');
        }

        $episodes = json_decode($responseJson);
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
        if (!Cache::has('locations')) {
            $client = $this->client->get($this->url . "/location");
            $responseJson = $client->getBody()->getContents();
            Cache::save('locations', $responseJson);
        } else {
            $responseJson = Cache::get('locations');
        }

        $locations = json_decode($responseJson);
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
        if (!Cache::has('episode_' . $number)) {
            $client = $this->client->get($this->url . "/episode/$number");
            $responseJson = $client->getBody()->getContents();
            Cache::save('episode_' . $number, $responseJson);
        } else {
            $responseJson = Cache::get('episode_' . $number);
        }

        $episodes = json_decode($responseJson);

        $id = [];
        foreach ($episodes->characters as $character) {
            $id[] = basename($character);
        }
        $characterIds = implode(",", $id);

        if (!Cache::has('episode_' . $number . '_characters')) {
            $getCharacters = $this->client->get($this->url . "/character/$characterIds");
            $charactersJson = $getCharacters->getBody()->getContents();
        } else {
            $charactersJson = Cache::get('episode_' . $number . '_characters');
        }

        $characters = json_decode($charactersJson);
        $pages = $characters->info;

        foreach ($characters as $person) {
            if (!Cache::has($person->name . 'episode_1')) {
                $firstEpisode = $this->client->get($person->episode[0]);
                $firstEpisodeJson = $firstEpisode->getBody()->getContents();
            } else {
                $firstEpisodeJson = Cache::get($person->name . 'episode_1');
            }

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
        if (!Cache::has('characters_location' . $number)) {
            $client = $this->client->get($this->url . "/location/$number");
            $locationJson = $client->getBody()->getContents();
        } else {
            $locationJson = Cache::get('characters_location_' . $number);
        }

        $location = json_decode($locationJson);

        $id = [];
        foreach ($location->residents as $character) {
            $id[] = basename($character);
        }
        $characterIds = implode(",", $id);

        if (!Cache::has('currently_in_location_' . $number)) {
            $getCharacters = $this->client->get($this->url . "/character/$characterIds");
            $charactersJson = $getCharacters->getBody()->getContents();
            Cache::save('currently_in_location_' . $number, $charactersJson);
        } else {
            $charactersJson = Cache::get('currently_in_location_' . $number);
        }

        $characters = json_decode($charactersJson);
        $pages = $characters->info;

        foreach ($characters as $person) {
            if (!Cache::has('first_character_episode')) {
                $firstEpisode = $this->client->get($person->episode[0]);
                $firstEpisodeJson = $firstEpisode->getBody()->getContents();
                Cache::save('first_character_episode', $firstEpisodeJson);
            } else {
                $firstEpisodeJson = Cache::get('first_character_episode');
            }

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
        if (!Cache::has('episode_number_' . $number)) {
            $client = $this->client->get($this->url . "/episode/$number");
            $episodesJson = $client->getBody()->getContents();
            Cache::save('episode_number_' . $number, $episodesJson);
        } else {
            $episodesJson = Cache::get('episode_number_' . $number);
        }

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
        if (!Cache::has('location_number_' . $number)) {
            $client = $this->client->get($this->url . "/location/$number");
            $locationJson = $client->getBody()->getContents();
            Cache::save('location_number_' . $number, $locationJson);
        } else {
            $locationJson = Cache::get('location_number_' . $number);
        }

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