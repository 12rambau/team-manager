<?php

namespace App\Repository;

use App\Entity\PersonnalStat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PersonnalStat|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonnalStat|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonnalStat[]    findAll()
 * @method PersonnalStat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonnalStatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonnalStat::class);
    }
}
