<?php

namespace App\Controller;

use App\DTO\AlbumDTORequest;
use App\Entity\Album;
use App\Repository\AlbumRepository;
use App\Service\AlbumService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class AlbumController extends AbstractController
{
    const BAD_REQUEST_STATUS_CODE = 400;
    const CREATED_STATUS_CODE = 201;

    #[Route('/albums', name: 'app_album', methods: ['GET'])]
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

    #[Route('/albums', name: 'app_album_create', methods: ['POST'])]
    public function create(Request $request, AlbumRepository $repository, ValidatorInterface $validator, SerializerInterface $serializer): JsonResponse
    {
        $album = $serializer->deserialize($request->getContent(), Album::class, 'json');
        $errors = $validator->validate($album);
        if (count($errors) === 0) {
            $repository->save($album, true);

            return $this->json(
                ['data' => $album],
                self::CREATED_STATUS_CODE
            );
        } else {
            return $this->json(
                [ 'errors' => $this->buildErrorMessages($errors) ],
                self::BAD_REQUEST_STATUS_CODE
            );
        }
    }

    #[Route('/albums/{id}', name: 'app_album_update', methods: ['PUT'])]
    public function edit(Request $request, int $id, AlbumRepository $repository, ValidatorInterface $validator): JsonResponse
    {
        $album = $repository->find($id);

        if (!$album) {
            return $this->json(
                ['errors' => [['source' => 'id', 'detail' => 'does not exist']]],
                self::BAD_REQUEST_STATUS_CODE
            );
        }

        $body_data = json_decode($request->getContent(), true);
        if (!empty($body_data['title'])) {
            $album->setTitle($body_data['title']);
        }

        $errors = $validator->validate($album);
        if (count($errors) === 0) {
            $repository->save($album, true);

            return $this->json(
                ['data' => $album],
            );
        } else {
            return $this->json(
                [ 'errors' => $this->buildErrorMessages($errors) ],
                self::BAD_REQUEST_STATUS_CODE
            );
        }
    }

    #[Route('/albums/{id}', name: 'app_album_delete', methods: ['DELETE'])]
    public function delete(Request $request, int $id, AlbumRepository $repository): JsonResponse
    {
        $album = $repository->find($id);

        if (!$album) {
            return $this->json(
                ['errors' => [['source' => 'id', 'detail' => 'does not exist']]],
                self::BAD_REQUEST_STATUS_CODE
            );
        }

        $repository->remove($album, true);

        return $this->json($album);
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
