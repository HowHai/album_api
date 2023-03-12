<?php

namespace App\Service;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use App\DTO\AlbumDTOResponse;

class ItuneAPIServiceServerError extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Itune service unavailable: {$message}");
    }
}

class ItuneAPIService
{
    private GuzzleClient $client;

    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }

    /**
     * Return a list of albums
     *
     * @param int $limit Maximum number of albums to return
     *
     * @throws ItuneAPIServiceServerError
     * @return array
     */
    public function getAlbums(int $limit): array
    {
        $albums = [];

        try {
            $response = $this->client->get("/us/rss/topalbums/limit={$limit}/json");
            if ($response->getStatusCode() == 200) {
                $feed_data = json_decode($response->getBody()->getContents(), true);
                foreach ($feed_data['feed']['entry'] as $album_entry) {
                    $album_dto = new AlbumDTOResponse();
                    $album_dto->setTitle($album_entry['title']['label']);

                    $albums[] = $album_dto;
                }
            } else {
                throw new ItuneAPIServiceServerError('something went wrong.');
            }
        } catch (RequestException $e) {
            throw new ItuneAPIServiceServerError('something went wrong.');
        }
        return $albums;
    }
}
