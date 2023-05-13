<?php declare(strict_types=1);

namespace App;

use App\Core\Renderer;
use App\Core\Router;

class App
{
    public function run(): void
    {
        if (isset($_GET["name"])) {
            $client = new Controllers\CharacterController();
            $response = $client->search(
                $_GET["name"],
                $_GET["status"],
                $_GET["species"]
            );
            $renderer = new Renderer('../app/Views');
            echo $renderer->render($response);
        } elseif (isset($_GET["page"])) {
            $client = new Controllers\CharacterController();
            $response = $client->changePage($_GET["page"]);
            $renderer = new Renderer('../app/Views');
            echo $renderer->render($response);
        } elseif (isset($_GET["episodes"])) {
            echo "works";
            $response = Router::response();
            $renderer = new Renderer('../app/Views');
            echo $renderer->render($response);
        } else {
            $response = Router::response();
            $renderer = new Renderer('../app/Views');
            echo $renderer->render($response);
        }
    }
}