<?php declare(strict_types=1);

namespace App\Models;

class Episode
{
    private string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function firstEpisodeTitle(): string
    {
        return $this->title;
    }
}