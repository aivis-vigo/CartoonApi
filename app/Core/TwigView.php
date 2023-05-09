<?php declare(strict_types=1);

namespace App\Core;

class TwigView
{
    private string $path;
    private array $data;

    public function __construct(string $path, array $data)
    {
        $this->path = $path;
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getPath(): string
    {
        return $this->path . ".html.twig";
    }
}