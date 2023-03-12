<?php

namespace App\Repository;

use App\Entity\Album;
use App\Service\AlbumService;
use App\DTO\AlbumDTOResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Album>
 *
 * @method Album|null find($id, $lockMode = null, $lockVersion = null)
 * @method Album|null findOneBy(array $criteria, array $orderBy = null)
 * @method Album[]    findAll()
 * @method Album[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }

    public function save(Album $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Album $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param int $limit Maximum number of albums to return
     * @param string $sort Sort option
     *
     * @return array
     */
    public function getAlbums(int $limit, string $sort): array
    {
        $albums = [];
        $query = $this->createQueryBuilder('album');

        if ($sort === AlbumService::SORT_BY_TITLE) {
            $query->orderBy('album.title', 'ASC');
        }
        $album_entities = $query
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        foreach ($album_entities as $entity) {
            $album_dto = new AlbumDTOResponse();
            $album_dto->setTitle($entity->getTitle());

            $albums[] = $album_dto;
        }

        return $albums;
    }
}
