<?php

namespace App\Repository;

use App\Entity\Structure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Structure>
 *
 * @method Structure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Structure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Structure[]    findAll()
 * @method Structure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StructureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Structure::class);
    }

    public function add(Structure $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Structure $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchByName(string $name): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT s
            FROM App\Entity\Structure s
            LEFT JOIN App\Entity\User u WITH s.user = u.id
            WHERE u.nom LIKE :nom
            ORDER BY u.nom ASC"
        )->setParameter('nom', '%'.$name.'%');
        
        return $query->getResult();
    }

    public function filterByStatus(string $status): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s
            FROM App\Entity\Structure s
            LEFT JOIN App\Entity\User u WITH s.user = u.id
            WHERE u.is_active = :status
            ORDER BY u.nom ASC'
        )->setParameter('status', $status);

        return $query->getResult();
    }

}
