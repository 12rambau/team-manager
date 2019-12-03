<?php

namespace App\Repository;

use App\Entity\FieldTemplate;
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

    public function queryEnabled()
    {
        $qb = $this->createQueryBuilder('t');

        $qb
            ->select('t')
            ->where('t.enable = :enable')
            ->setParameter('enable', true);

        return $qb;
    }

    // /**
    //  * @return FieldTemplate[] Returns an array of FieldTemplate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FieldTemplate
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
