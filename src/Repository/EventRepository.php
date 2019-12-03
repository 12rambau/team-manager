<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findbyDateInterval(\DateTime $start, \DateTime $end): array
    {
        $qb = $this->createQueryBuilder('ev');

        $qb->select('ev')
            ->where('ev.start < :end')
            ->andWhere('ev.finish > :start')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
        ;

        $result = $qb->getQuery()->getResult();

        return ($result)? $result:[];  
    }

    public function countAll()
    {
        $qb = $this->createQueryBuilder('ev');

        $qb->select('COUNT(ev)');

        $result = $qb->getQuery()->getSingleScalarResult();

        return ($result)?$result:0;
    }

    /**
     * Find the event around a date 
     * 
     * @param int $nbEvents
     *  the number of events to fetch in each direction
     * @param Event $currentEvent
     *  the current event to look around
     * 
     * @return array the list of events around the currentEvent
     */
    public function findAround(int $nbEvents, Event $currentEvent):array
    {

        $qb1 = $this->createQueryBuilder('ev');
        $qb1->select('ev')
            ->where('ev.start < :date')
            ->setParameter('date', $currentEvent->getStart())
            ->orderBy('ev.start', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults($nbEvents);

        $qb2 = $this->createQueryBuilder('ev');
        $qb2->select('ev')
            ->where('ev.start > :date')
            ->setParameter('date', $currentEvent->getStart())
            ->orderBy('ev.start', 'ASC')
            ->setFirstResult(0)
            ->setMaxResults($nbEvents);
        
        $before = $qb1->getQuery()->getResult();
        $after = $qb2->getQuery()->getResult();

        $events = [];
        $length = count($before);
        for ($i=$length-1; $i >= 0; $i--)
            array_push($events, $before[$i]);

        array_push($events, $currentEvent);

        $length = count($after);
        for ($i=0; $i < $length; $i++)
            array_push($events, $after[$i]);
        
        return $events;
    }
}
