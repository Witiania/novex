<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function delete(User $user): void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

    public function edit(): void
    {
        $this->getEntityManager()->flush();
    }

    /**
     *
     * @param int $page
     * @return Paginator
     */
    public function getAllUsersPaginator(int $page = 1): Paginator
    {
        $query = $this->createQueryBuilder('u')
            ->setMaxResults(50)
            ->setFirstResult(($page - 1) * 50)
            ->getQuery();

        return new Paginator($query);
    }
}