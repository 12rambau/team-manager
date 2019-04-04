<?php

namespace App\Repository;

use App\Entity\Field;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Field|null find($id, $lockMode = null, $lockVersion = null)
 * @method Field|null findOneBy(array $criteria, array $orderBy = null)
 * @method Field[]    findAll()
 * @method Field[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FieldRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Field::class);
    }

    public function findAllTemplate()
    {
        $qb = $this->createQueryBuilder('t');

        $qb->select('t')
            ->where('t.name LIKE :regex')
            ->setParameter('regex', 'template_%')
        ;

        return $qb->getQuery()->getResult();
    }

    public function queryAllTemplate()
    {
        $qb = $this->createQueryBuilder('t');

        $qb->select('t')
            ->where('t.name LIKE :regex')
            ->setParameter('regex', 'template_%')
        ;

        return $qb;
    }
}
