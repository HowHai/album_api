<?php

declare(strict_types=1);

namespace App\DTO;


use Symfony\Component\Validator\Constraints as Assert;

class AlbumDTORequest
{

    #[Assert\Type('numeric')]
    private $limit;

    public function setLimit($limit): void
    {
        $this->limit = $limit;
    }

    public function getLimit(): string
    {
        return $this->limit;
    }
}
