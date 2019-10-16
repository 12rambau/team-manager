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

    public function queryActivated()
    {
        $qb = $this->createQueryBuilder('t');

        $qb
            ->select('t')
            ->where('t.active = :active')
            ->setParameter('active', true);

        return $qb;
    }
}
