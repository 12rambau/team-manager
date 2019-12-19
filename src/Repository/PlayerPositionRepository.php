<?php

namespace App\Repository;

use App\Entity\PlayerPosition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PlayerPosition|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerPosition|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerPosition[]    findAll()
 * @method PlayerPosition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerPositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerPosition::class);
    }
}
