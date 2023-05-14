<?php declare(strict_types=1);

namespace App\Models;

class Location
{
    private string $name;
    private string $type;
    private string $dimension;
    private Page $page;
    private array $residents;

    public function __construct(
        string $name,
        string $type,
        string $dimension,
        Page $page,
        array $residents
    )
    {
        $this->name = $name;
        $this->type = $type;
        $this->dimension = $dimension;
        $this->page = $page;
        $this->residents = $residents;
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
        return $this->dimension;
    }

    public function page(): Page
    {
        return $this->page;
    }

    public function residents(): array
    {
        return $this->residents;
    }
}