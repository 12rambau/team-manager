<?php

namespace App\Repository;

use App\Entity\EventTag;
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

    // /**
    //  * @return EventTag[] Returns an array of EventTag objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventTag
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
