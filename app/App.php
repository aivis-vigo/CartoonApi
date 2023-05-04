<?php declare(strict_types=1);

namespace App;
use App\Controllers\ApiClient;
use App\Models\Character;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class App
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }

    public function run(): void
    {
        $loader = new FilesystemLoader('../app/Views');
        $twig = new Environment($loader);

        echo $twig->render("view.html.twig", [
            "characters" => $this->client->fetchCharacters(),
            "lastSeenIn" => "Last known location:",
            "firstSeenIn" => "First seen in:"
        ]);
    }
}