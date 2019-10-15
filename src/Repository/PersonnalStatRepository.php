<?php

namespace App\Repository;

use App\Entity\PersonnalStat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PersonnalStat|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonnalStat|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonnalStat[]    findAll()
 * @method PersonnalStat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonnalStatRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PersonnalStat::class);
    }

    // /**
    //  * @return PersonnalStat[] Returns an array of PersonnalStat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PersonnalStat
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
