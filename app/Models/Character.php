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
    private string $firstSeen;
    private string $pictureUrl;

    public function __construct(
        $id,
        $name,
        $status,
        $species,
        $originUrl,
        $lastSeen,
        $firsEpisodeUrl,
        $firstSeen,
        $pictureUrl
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
        if ($this->status == "Alive") {
            return "yellowgreen";
        } elseif ($this->status == "Dead") {
            return "red";
        }
        return "lightgray";
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

    public function firstSeen(): string
    {
        return $this->firstSeen;
    }

    public function pictureUrl(): string
    {
        return $this->pictureUrl;
    }
}