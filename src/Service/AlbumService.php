<?php

namespace App\Service;

use App\Service\ItuneAPIService;
use App\Repository\AlbumRepository;

class AlbumService
{
    const MAXIMUM_NUMBER_OF_ALBUMS = 100;
    const SOURCE_ITUNE = 'itune';
    const SORT_BY_TITLE = 'title';
    private ItuneAPIService $itune_service;
    private AlbumRepository $repository;

    public function __construct(ItuneAPIService $itune_service, AlbumRepository $repository)
    {
        $this->itune_service = $itune_service;
        $this->repository = $repository;
    }

    /**
     * Return a list of albums
     *
     * @param int $limit Maximum number of albums to return
     * @param string $source Source of data to fetch albums from
     * @param string $sort Sort option
     *
     * @return array
     */
    public function getAlbums(int $limit = self::MAXIMUM_NUMBER_OF_ALBUMS, string $source = self::SOURCE_ITUNE, string $sort = ''): array
    {
        if ($source == self::SOURCE_ITUNE) {
            return $this->itune_service->getAlbums($limit, $sort);
        } else {
            return $this->repository->getAlbums($limit, $sort);
        }
    }
}
