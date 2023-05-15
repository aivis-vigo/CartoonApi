<?php declare(strict_types=1);

namespace App\Models;

class Episode
{
    private int $id;
    private string $title;
    private string $released;
    private string $seasonEpisodeId;
    private array $characters;
    private Page $page;

    public function __construct(
        int    $id,
        string $title,
        string $released,
        string $seasonEpisodeId,
        array  $characters,
        Page   $page
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->released = $released;
        $this->seasonEpisodeId = $seasonEpisodeId;
        $this->characters = $characters;
        $this->page = $page;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function released(): string
    {
        return $this->released;
    }

    public function seasonEpisodeId(): string
    {
        return $this->seasonEpisodeId;
    }

    public function charcters(): array
    {
        return $this->characters;
    }

    public function page(): Page
    {
        return $this->page;
    }
}