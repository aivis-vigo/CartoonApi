<?php declare(strict_types=1);

namespace App\Models;

class Character
{
    private int $id;
    private string $name;
    private string $status;
    private string $species;
    private string $originUrl;
    private string $lastSeen;
    private string $firstEpisodeUrl;
    private Episode $firstSeen;
    private string $pictureUrl;
    private Page $pageUrl;

    public function __construct(
        int     $id,
        string  $name,
        string  $status,
        string  $species,
        string  $originUrl,
        string  $lastSeen,
        string  $firsEpisodeUrl,
        Episode $firstSeen,
        string  $pictureUrl,
        Page    $pageUrl
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->species = $species;
        $this->originUrl = $originUrl;
        $this->lastSeen = $lastSeen;
        $this->firstEpisodeUrl = $firsEpisodeUrl;
        $this->firstSeen = $firstSeen;
        $this->pictureUrl = $pictureUrl;
        $this->pageUrl = $pageUrl;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function status(): string
    {
        return ucfirst($this->status);
    }

    public function statusColor(): string
    {
        switch ($this->status) {
            case "Alive":
                return "yellowgreen";
            case "Dead":
                return "red";
            default:
                return "lightgray";
        }

    }

    public function species(): string
    {
        return $this->species;
    }

    public function originUrl(): string
    {
        return $this->originUrl;
    }

    public function lastSeen(): string
    {
        return ucfirst($this->lastSeen);
    }

    public function firstEpisodeUrl(): string
    {
        return $this->firstEpisodeUrl;
    }

    public function firstSeen(): Episode
    {
        return $this->firstSeen;
    }

    public function pictureUrl(): string
    {
        return $this->pictureUrl;
    }

    public function pageUrl(): Page
    {
        return $this->pageUrl;
    }
}