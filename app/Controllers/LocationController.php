<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\Core\TwigView;

class LocationController
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }

    public function allLocations(): TwigView
    {
        $locations = $this->client->fetchLocations();
        return new TwigView('locations', [
            'locations' => $locations,
            'pages' => $locations[0]->page()
        ]);
    }

    public function selectLocation(string $number): TwigView
    {
        return new TwigView('selectedLocation', [
            'location' => $this->client->selectedLocation($number),
            'characters' => $this->client->locationResidents($number),
            'lastSeenIn' => 'Last known location:',
            'firstSeenIn' => 'First seen in:'
        ]);
    }
}