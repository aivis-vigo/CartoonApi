<?php declare(strict_types=1);

namespace App\Controllers;
use App\ApiClient;

class CharacterController
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }

    public function route(): string
    {
        return ($_SERVER["REQUEST_URI"] == "/") ? "view.html.twig" : "";
    }

    public function randomlySelected(): array
    {
        return $this->client->fetchCharacters();
    }
}