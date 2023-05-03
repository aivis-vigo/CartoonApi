<?php declare(strict_types=1);

namespace App;
use App\Controllers\ApiClient;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Models\Character;

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