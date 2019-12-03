<?php

namespace App\Repository;

use App\Entity\EventTag;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EventTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventTag[]    findAll()
 * @method EventTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventTagRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EventTag::class);
    }

    public function queryActivated(Team $team = null)
    {
        $qb = $this->createQueryBuilder('t');

        $qb
            ->select('t')
            ->where('t.active = :active')
            ->setParameter('active', true);

        //if ($team) {
            $qb
                ->andWhere('t.team = :team')
                ->setParameter('team', $team);
        //}

        return $qb;
    }
}
