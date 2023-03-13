<?php

declare(strict_types=1);

namespace App\DTO;


use Symfony\Component\Validator\Constraints as Assert;

class AlbumDTORequest
{

    #[Assert\Type('numeric')]
    private $limit;

    #[Assert\Type('string')]
    private $source;

    #[Assert\Type('string')]
    private $sort;

    public function setLimit($limit): void
    {
        $this->limit = $limit;
    }

    public function getLimit(): string
    {
        return $this->limit;
    }

    public function setSource($source): void
    {
        $this->source = $source;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSort($sort): void
    {
        $this->sort = $sort;
    }

    public function getSort(): string
    {
        return $this->sort;
    }
}
