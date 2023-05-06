<?php declare(strict_types=1);

namespace App;

use App\Core\Router;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class App
{
    public function run(): void
    {
        $response = Router::response();

        $loader = new FilesystemLoader('../app/Views');
        $twig = new Environment($loader);

        echo $twig->render($response->getPath(), $response->getData());
    }
}