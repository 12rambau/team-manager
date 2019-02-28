<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Participation;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class CreateParticipations
{
    private $em; // EntityManager

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }
    public function postPersist(LifecycleEventArgs $args)
    {
        
        $event = $args->getObject();

        if ($event instanceof Event) { 

            $users = $this->em->getRepository(User::class)->findAll();
            $nbUser = count($users);
            for ($i=0; $i < $nbUser; $i++)
            {
                $participation = new Participation ();
                $participation->setUser($users[$i]);
                $participation->setEvent($event);
                
                $this->em->persist($participation);
            }

            $this->em->flush();
        }

        return;
    }
}