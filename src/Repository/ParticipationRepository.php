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

    //TODO check the utility of all thoses find function (not sure they are usefull)
    
    public function FindByEventAndUser(Event $event, User $user)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p')
            ->where('p.user = :user')
            ->setParameter('user', $user)
            ->andWhere('p.event = :event')
            ->setParameter('event', $event)
            ->getFirstResult()
        ;

        return $qb->getQuery()->getResult()[0]; //as there is only one result
        
    }

    public function findTenByUser(int $offset, User $user)
    {
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

    public function queryAllIn(Event $event)
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->select('p')
            ->where('p.event = :event')
            ->setParameter('event', $event)
            ->andWhere('p.value = :value')
            ->setParameter('value', true);

        return $qb;
    }
}
