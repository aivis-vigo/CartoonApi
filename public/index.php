<?php declare(strict_types=1);

require_once "../vendor/autoload.php";

$client = new App\App();
$client->run();

$test = new App\ApiClient();
$response = $test->fetchLocations();
var_dump($response[0]);