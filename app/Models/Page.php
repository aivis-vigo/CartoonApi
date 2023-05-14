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
        /**
        if (empty($this->previousPage)) {
            return null;
        }
         * */
        $url = parse_url($this->previousPage);
        parse_str($url["query"], $page);
        return $page["page"];
    }

    public function nextPage(): ?string
    {
        $url = parse_url($this->nextPage);
        parse_str($url["query"], $page);
        return $page["page"];
    }
}