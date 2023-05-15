<?php declare(strict_types=1);

namespace App\Models;

class Page
{
    private ?string $previousPage;
    private ?string $nextPage;

    public function __construct(
        ?string $previousPage,
        ?string $nextPage
    )
    {
        $this->previousPage = $previousPage;
        $this->nextPage = $nextPage;
    }

    public function previousPage(): ?string
    {
        if (empty($this->previousPage)) {
            return null;
        }
        return basename($this->previousPage);
    }

    public function nextPage(): ?string
    {
        return basename($this->nextPage);
    }
}