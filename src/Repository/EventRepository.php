<?php

namespace App\Repository;

use App\Entity\Event;
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
    public function findTen(int $offset)
    {
        $qb = $this->createQueryBuilder('ev');

        $qb->select(array('ev'))
            ->orderBy('ev.start', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults(10);

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
}
