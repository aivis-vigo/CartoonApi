<?php declare(strict_types=1);

namespace App\Core;

use App\Controllers\CharacterController;
use App\Controllers\EpisodeController;
use FastRoute;
use function FastRoute\simpleDispatcher;

class Router
{
    public static function response(): ?TwigView
    {
        $dispatcher = simpleDispatcher(function (FastRoute\RouteCollector $router) {
            $router->addRoute('GET', '/', [CharacterController::class, 'index']);
            $router->addRoute('GET', '/characters', [CharacterController::class, 'allCharacters']);
            $router->addRoute('GET', '/allCharacters', [CharacterController::class, 'allCharacters']);
            $router->addRoute('GET', '/?page[/{title}]', [CharacterController::class, 'changePage']);
            $router->addRoute('GET', '/episodes', [EpisodeController::class, 'allEpisodes']);
        });

        // Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                return null;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                return null;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                [$controllerName, $methodName] = $handler;

                if (!empty($vars)) {
                    /** @var TwigView $response */
                    return (new $controllerName)->{$methodName}($vars['title']);
                }

                /** @var TwigView $response */
                return (new $controllerName)->{$methodName}($controllerName);
        }
        return null;
    }
}