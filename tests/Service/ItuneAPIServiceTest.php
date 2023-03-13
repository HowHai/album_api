<?php

namespace App\Tests\Service;

use App\DTO\AlbumDTOResponse;
use App\Service\ItuneAPIService;
use App\Service\ItuneAPIServiceServerError;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ItuneAPIServiceTest extends KernelTestCase
{
    public function testGetAlbums()
    {
        $album_dto = new AlbumDTOResponse();
        $album_dto->setTitle('All eyez on me');
        $response_mock = [
            'feed' => [
                'entry' => [
                    [ 'title' => [ 'label' => $album_dto->getTitle()]]
                ]
            ]
        ];
        $client_mock = new MockHandler([
            new Response(200, [], json_encode($response_mock)),
        ]);
        $handlerStack = HandlerStack::create($client_mock);
        $client = new GuzzleClient(['handler' => $handlerStack]);
        $service = new ItuneAPIService($client);

        $result = $service->getAlbums(1);

        $this->assertEquals([$album_dto], $result);
    }

    public function testGetAlbumsWhenServerError()
    {
        $client_mock = new MockHandler([
            new Response(502, [], ''),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);
        $handlerStack = HandlerStack::create($client_mock);
        $client = new GuzzleClient(['handler' => $handlerStack]);
        $service = new ItuneAPIService($client);

        $this->expectException(ItuneAPIServiceServerError::class);
        $service->getAlbums(1);
        $service->getAlbums(1);
    }
}
