<?php

namespace App\Repository;

use App\Entity\EventTag;
use App\Entity\Event;
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

    public function queryActivatedAndEvent(Event $event)
    {
        $qb = $this->createQueryBuilder('t');

        $qb
            ->select(['t','e'])
            ->leftJoin('t.events', 'e')
            ->where('t.active = :active')
            ->setParameter('active', true)
            ->orWhere('e = :event')
            ->setParameter('event', $event);

        return $qb;
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

    public function findActivated(){
        return $this->queryActivated()->getQuery()->getResult();
    }
}
