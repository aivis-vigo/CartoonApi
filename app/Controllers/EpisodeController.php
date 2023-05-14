<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\Core\TwigView;

class EpisodeController
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }

    public function allEpisodes(): TwigView
    {
        return new TwigView('episodes', [
            'episodes' => $this->client->fetchEpisodes(),
            //'pages' =>$this->client->fetchEpisodes()[0]->page
        ]);
    }
}