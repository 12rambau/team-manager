<?php

namespace App\Repository;

use App\Entity\FieldTemplate;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FieldTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method FieldTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method FieldTemplate[]    findAll()
 * @method FieldTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FieldTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FieldTemplate::class);
    }

    public function queryEnabled(Team $team)
    {
        $qb = $this->createQueryBuilder('t');

        $qb
            ->select('t')
            ->where('t.enable = :enable')
            ->setParameter('enable', true);

        if ($team) {
            $qb
                ->andWhere('t.team = :team')
                ->setParameter('team', $team);
        }

        return $qb;
    }
}
