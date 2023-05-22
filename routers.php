<?php declare(strict_types=1);

use App\Controllers\CharacterController;
use App\Controllers\EpisodeController;
use App\Controllers\LocationController;

return [
    ['GET', '/', [CharacterController::class, 'index']],
    ['GET', '/episodes', [EpisodeController::class, 'index']],
    ['GET', '/episodes/{id:\d+}', [EpisodeController::class, 'show']],
    ['GET', '/locations', [LocationController::class, 'index']],
    ['GET', '/locations/{id:\d+}', [LocationController::class, 'show']],
    ['GET', '/characters', [CharacterController::class, 'index']],
    ['GET', '/allCharacters', [CharacterController::class, 'showAll']],
    ['GET', '/?page/{id:\d+}', [CharacterController::class, 'changePage']]
];
