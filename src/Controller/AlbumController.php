<?php

namespace App\Controller;

use App\Service\AlbumService;
use App\DTO\AlbumDTORequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class AlbumController extends AbstractController
{
    const BAD_REQUEST_STATUS_CODE = 400;

    #[Route('/albums', name: 'app_album')]
    public function index(AlbumService $album_service, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $request_dto = new AlbumDTORequest();
        $request_dto->setLimit($request->query->get('limit', AlbumService::MAXIMUM_NUMBER_OF_ALBUMS));
        $request_dto->setSource($request->query->get('source', AlbumService::SOURCE_ITUNE));
        $request_dto->setSort($request->query->get('sort', ''));

        $errors = $validator->validate($request_dto);
        if (count($errors) === 0) {
            $albums = $album_service->getAlbums(
                $request_dto->getLimit(),
                $request_dto->getSource(),
                $request_dto->getSort()
            );
            return $this->json(['data' => $albums]);
        } else {
            return $this->json(
                [ 'errors' => $this->buildErrorMessages($errors) ],
                self::BAD_REQUEST_STATUS_CODE
            );
        }
    }

    private function buildErrorMessages(ConstraintViolationList $errors): array
    {
        $error_messages = [];
        foreach ($errors as $violation) {
            $error_messages[] = [
                'source' => $violation->getPropertyPath(),
                'detail' => $violation->getMessage(),
            ];
        }

        return $error_messages;
    }
}
