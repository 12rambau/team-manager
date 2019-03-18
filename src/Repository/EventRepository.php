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

    /**
     * @return events[] Returns an array of 10 events
     */
    public function findTen(int $offset, int $nbEvent)
    {
        $qb = $this->createQueryBuilder('ev');

        $qb->select(array('ev'))
            ->orderBy('ev.start', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($nbEvent);

        return $qb->getQuery()->getResult();

    }

    /**
     * @return events[] Returns an array of  nbEvent events if the User
     */
    public function findTenByUser(int $offset, int $nbEvent, User $user)
    {
        $qb = $this->createQueryBuilder('ev');

        $qb->select('ev')
            ->join('ev.participations', 'p')
            ->where('p.user = :user')
            ->setParameter('user', $user)
            ->orderBy('ev.start', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($nbEvent);

        return $qb->getQuery()->getResult();

    }

    public function findWeeklyEvent(\DateTime $monday):array
    {
        $sunday = new \DateTime('@'.$monday->getTimestamp());
        $sunday->add(new \DateInterval('P6DT23H59M59S'));

        $qb = $this->createQueryBuilder('ev');

        $qb->select('ev')
            ->where('ev.start < :sunday')
            ->andWhere('ev.finish > :monday')
            ->setParameter('monday', $monday)
            ->setParameter('sunday', $sunday)
        ;

        $result = $qb->getQuery()->getResult();

        return ($result)? $result:[];
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

    /**
     * @return int the number of events in the dataBase with this user
     */
    public function countUserEvent(User $user)
    {
        $qb = $this->createQueryBuilder('ev');

        $qb->select('COUNT(ev)')
            ->leftJoin('ev.participations', 'p')
            ->where('p.user = :user')
            ->setParameter('user', $user);

        $result = $qb->getQuery()->getSingleScalarResult();

        return ($result)?$result:0;

    }

    /**
     * @return array the list of x events around the currentEvent
     */
    public function findAround(int $nbEvents, Event $currentEvent):array
    {

        $qb1 = $this->createQueryBuilder('ev');
        $qb1->select('ev')
            ->where('ev.start < :date')
            ->setParameter('date', $currentEvent->getStart())
            ->orderBy('ev.start', 'DESC')
            ->setFirstResult(1)
            ->setMaxResults($nbEvents);

        $qb2 = $this->createQueryBuilder('ev');
        $qb2->select('ev')
            ->where('ev.start > :date')
            ->setParameter('date', $currentEvent->getStart())
            ->orderBy('ev.start', 'ASC')
            ->setFirstResult(1)
            ->setMaxResults($nbEvents);
        
        $before = $qb1->getQuery()->getResult();
        $after = $qb2->getQuery()->getResult();

        $events = [];
        for ($i=$nbEvents-1; $i >= 0; $i--)
            array_push($events, $before[$i]);

        array_push($events, $currentEvent);

        for ($i=0; $i < $nbEvents; $i++)
            array_push($events, $after[$i]);
        
        return $events;
    }
}
