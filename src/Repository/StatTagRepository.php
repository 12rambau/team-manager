<?php

namespace App\Repository;

use App\Entity\StatTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StatTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatTag[]    findAll()
 * @method StatTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatTagRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StatTag::class);
    }

    public function queryActivated($team = null)
    {
        $qb = $this->createQueryBuilder('t');

        $qb
            ->select('t')
            ->where('t.active = :active')
            ->setParameter('active', true);

        if ($team) {
            $qb
                ->andWhere('t.team = :team')
                ->setParameter('team', $team);
        }

        return $qb;
    }
}
