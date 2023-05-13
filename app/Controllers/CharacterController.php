<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\Core\TwigView;
use App\Models\Page;

class CharacterController
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }

    public function index(): TwigView
    {
        return new TwigView('view', [
            'characters' => $this->client->fetchRandomCharacters(),
            "lastSeenIn" => "Last known location:",
            "firstSeenIn" => "First seen in:",
            //"pages" => $this->client->fetchCharacters()[0]->pageUrl()
        ]);
    }

    public function allCharacters(): TwigView
    {
        return new TwigView('characters', [
            'characters' => $this->client->fetchCharacters(),
            "lastSeenIn" => "Last known location:",
            "firstSeenIn" => "First seen in:",
            "pages" => $this->client->fetchCharacters()[0]->pageUrl()
        ]);
    }

    public function search(
        string $name,
        string $status,
        string $species
    ): TwigView
    {
        return new TwigView('search', [
            'characters' => $this->client->searchFor(
                $name,
                $status,
                $species
            ),
            "lastSeenIn" => "Last known location:",
            "firstSeenIn" => "First seen in:",
        ]);
    }

    public function changePage(string $page): TwigView
    {
        return new TwigView('characters', [
            'characters' => $this->client->pageChanger($page),
            "lastSeenIn" => "Last known location:",
            "firstSeenIn" => "First seen in:",
            "pages" => $this->client->pageChanger($page)[0]->pageUrl()
        ]);
    }
}