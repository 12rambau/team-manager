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

    public function findMyByEvents($events, User $user)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select(array('p'))
            ->from('Participation', 'p')
            ->where('p.user.id = :userId')
            ->andWhere(':exprEvents')
            ->setParameter('userId', $user->getId());
        
        $nbEvent = count($events);
        $qbEvent = $this->createQueryBuilder('p');
        for ($i=0; $i < $nbEvent; $i++)
        {
            $qbEvent->expr()->eq('p.event.id', ':eventId')
                    ->setParameter('eventId', $events[$i]->getId());
        }

        $qb->setParameter('exprEvents', $qbEvent)
            ->order('p.event.start', 'ASC')
        ;
        
        return $qb->getQuery()->getRsult();
    }
}
