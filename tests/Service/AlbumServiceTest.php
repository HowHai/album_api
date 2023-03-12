<?php

namespace App\Tests\Service;

use App\Service\AlbumService;
use App\Service\ItuneAPIService;
use App\DTO\AlbumDTOResponse;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AlbumServiceTest extends KernelTestCase
{
    public function testGetAlbums()
    {
        self::bootKernel();
        $album_dto = new AlbumDTOResponse();
        $album_dto->setTitle('All eyez on me');
        $container = static::getContainer();
        $itune_service_mock = $this->createMock(ItuneAPIService::class);
        $itune_service_mock->expects(self::once())
            ->method('getAlbums')
            ->willReturn([$album_dto]);
        $service = new AlbumService($itune_service_mock);

        $result = $service->getAlbums();

        $this->assertEquals([$album_dto], $result);
    }
}
