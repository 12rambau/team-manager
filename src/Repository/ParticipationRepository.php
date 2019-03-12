<?php

namespace App\Repository;

use App\Entity\Participation;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\User;

/**
 * @method Participation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participation[]    findAll()
 * @method Participation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Participation::class);
    }

    public function getByUsername(User $user)
    {
        
    }

    public function findTenByUser( User $user)
    {
        $offset = 0;

        $qb = $this->createQueryBuilder('p');

        $qb->select(array('p','e'))
            ->where('p.user = :user')
            ->leftJoin('p.event', 'e')
            ->setParameter('user', $user)
            ->orderBy('e.start', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults(10);
        ;
        
        return $qb->getQuery()->getResult();
    }
}
