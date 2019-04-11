<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Participation;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class CreateParticipations
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $event = $args->getObject();

        if ($event instanceof Event) { 

            $users = $em->getRepository(User::class)->findAll();
            $nbUser = count($users);
            for ($i=0; $i < $nbUser; $i++)
            {
                $participation = new Participation ();
                $participation->setUser($users[$i]);
                $participation->setEvent($event);
                
                $em->persist($participation);
            }

            $em->flush();
        }

        return;
    }
}