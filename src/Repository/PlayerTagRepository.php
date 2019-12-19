<?php

namespace App\Repository;

use App\Entity\PlayerTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PlayerTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerTag[]    findAll()
 * @method PlayerTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerTag::class);
    }
}
