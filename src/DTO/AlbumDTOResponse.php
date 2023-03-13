<?php

declare(strict_types=1);

namespace App\DTO;

class AlbumDTOResponse
{
    private string $title;

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
