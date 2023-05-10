<?php declare(strict_types=1);

use App\Core\Renderer;
use App\Core\Router;

require_once "../vendor/autoload.php";

if (isset($_GET["page"])) {
    echo "link works";
}

$response = Router::response();
$renderer = new Renderer('../app/Views');
echo $renderer->render($response);