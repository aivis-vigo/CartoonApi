<?php declare(strict_types=1);

namespace App\Core;

use App\Controllers\CharacterController;
use FastRoute;
use function FastRoute\simpleDispatcher;

class Router
{
    public static function response(): ?TwigView
    {
        $dispatcher = simpleDispatcher(function(FastRoute\RouteCollector $router) {
            $router->addRoute('GET', '/', [CharacterController::class, 'index']);
            $router->addRoute('GET', '/characters', [CharacterController::class, 'index']);
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

                /** @var TwigView $response */
                $response = (new $controllerName)->{$methodName}();

                return $response;
        }
        return null;
    }
}