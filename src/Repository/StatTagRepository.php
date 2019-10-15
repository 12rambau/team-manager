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

    // /**
    //  * @return StatTag[] Returns an array of StatTag objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatTag
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
