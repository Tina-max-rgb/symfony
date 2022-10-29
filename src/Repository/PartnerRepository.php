<?php

namespace App\Repository;

use App\Entity\Partner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Partner>
 *
 * @method Partner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partner[]    findAll()
 * @method Partner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partner::class);
    }

    public function add(Partner $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Partner $entity, bool $flush = false): void
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
            'SELECT p
            FROM App\Entity\Partner p
            LEFT JOIN App\Entity\User u WITH p.user = u.id
            WHERE u.nom LIKE :nom
            ORDER BY u.nom ASC'
        )->setParameter('nom', '%'.$name.'%');

        return $query->getResult();
    }

    public function filterByStatus(string $status): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Partner p
            LEFT JOIN App\Entity\User u WITH p.user = u.id
            WHERE u.is_active = :status
            ORDER BY u.nom ASC'
        )->setParameter('status', $status);

        return $query->getResult();
    }
}
