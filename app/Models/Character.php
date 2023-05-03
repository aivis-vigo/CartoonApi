<?php declare(strict_types=1);

namespace App\Models;

class Character
{
    private string $name;
    private string $status;
    private string $species;
    private string $lastSeen;
    private string $firstSeen;
    private string $pictureUrl;

    public function __construct(
        $name,
        $status,
        $species,
        $lastSeen,
        $firstSeen,
        $pictureUrl
    )
    {
        $this->name = $name;
        $this->status = $status;
        $this->species = $species;
        $this->lastSeen = $lastSeen;
        $this->firstSeen = $firstSeen;
        $this->pictureUrl = $pictureUrl;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function species(): string
    {
        return $this->species;
    }

    public function lastSeen(): string
    {
        return $this->lastSeen;
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