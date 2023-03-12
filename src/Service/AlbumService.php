<?php

namespace App\Service;

use App\Service\ItuneAPIService;

class AlbumService
{
    const MAXIMUM_NUMBER_OF_ALBUMS = 100;
    private ItuneAPIService $itune_service;

    public function __construct(ItuneAPIService $itune_service)
    {
        $this->itune_service = $itune_service;
    }

    /**
     * Return a list of albums
     *
     * @param int $limit Maximum number of albums to return
     *
     * @return array
     */
    public function getAlbums(int $limit = self::MAXIMUM_NUMBER_OF_ALBUMS): array
    {
        return $this->itune_service->getAlbums($limit);
    }
}
