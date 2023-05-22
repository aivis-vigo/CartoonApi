<?php declare(strict_types=1);

use App\Core\Renderer;
use App\Core\Router;

require_once "../vendor/autoload.php";

$routes = require_once '../routers.php';
$response = Router::response($routes);
$renderer = new Renderer('../app/Views');
echo $renderer->render($response);