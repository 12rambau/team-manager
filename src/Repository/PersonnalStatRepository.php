<?php

namespace App\Repository;

use App\Entity\PersonnalStat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Event;
use App\Entity\User;

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

    //TODO check the utility of all thoses find function (not sure they are usefull)

    public function FindMyByEvent(Event $event, User $player)
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select('s','p','e')
            ->leftJoin('s.player','p')
            ->where('p = :player')
            ->setParameter('player', $player)
            ->leftJoin('s.event', 'e')
            ->andWhere('e = :event')
            ->setParameter('event', $event)
        ;

        return $qb->getQuery()->getResult();
        
    }

}
