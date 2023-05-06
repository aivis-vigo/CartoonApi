<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\Core\TwigView;

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
            'characters' => $this->client->fetchCharacters(),
            "lastSeenIn" => "Last known location:",
            "firstSeenIn" => "First seen in:"
        ]);
    }

    // todo: show single character
}