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

    public function findbyDateInterval($firstDate, $lastDate):arrayCollection
    {

        $qb = $this->createQueryBuilder();

        $qb->select(array('ev'))
            ->from('Event', 'ev')
            ->where($qb->expr()->between('ev.start', ':firstDate', ':lastDate'))
            ->order('ev.start', 'ASC')
            ->setParameters(
                [
                    'firstDate'=>$firstDate->format('Y-m-d H:i:s'),
                    'lastDate'=>$lastDate->format('Y-m-d H:i:s')
                ]
            )
        ;
        
        return $qb->getQuery()->getRsult();
    }
}
