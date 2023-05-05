<?php declare(strict_types=1);

namespace App;
use App\Controllers\CharacterController;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class App
{
    private CharacterController $controller;

    public function __construct()
    {
        $this->controller = new CharacterController();
    }

    public function run(): void
    {
        $loader = new FilesystemLoader('../app/Views');
        $twig = new Environment($loader);

        echo $twig->render($this->controller->route(), [
            "characters" => $this->controller->randomlySelected(),
            "lastSeenIn" => "Last known location:",
            "firstSeenIn" => "First seen in:"
        ]);
    }
}