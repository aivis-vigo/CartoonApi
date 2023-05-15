<?php declare(strict_types=1);

namespace App\Models;

class Location
{
    private int $id;
    private string $name;
    private string $type;
    private string $dimension;
    private Page $page;

    public function __construct(
        int    $id,
        string $name,
        string $type,
        string $dimension,
        Page   $page
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->dimension = $dimension;
        $this->page = $page;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function dimension(): string
    {
        return ucfirst($this->dimension);
    }

    public function page(): Page
    {
        return $this->page;
    }
}