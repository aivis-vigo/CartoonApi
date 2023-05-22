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

    public function index(): TwigView
    {
        $episodes = $this->client->fetchEpisodes();
        return new TwigView('episodes', [
            'episodes' => $episodes,
            'pages' =>$episodes[0]->page()
        ]);
    }

    public function show(string $number): TwigView
    {
        return new TwigView('selectedEpisode', [
            'episode' => $this->client->selectedEpisode($number),
            'characters' => $this->client->fetchEpisode($number),
            'lastSeenIn' => 'Last known location:',
            'firstSeenIn' => 'First seen in:'
        ]);
    }
}