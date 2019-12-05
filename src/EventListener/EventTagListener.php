<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\EventTag;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class EventTagListener
{
    public function prePersist (LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof EventTag)
        {
            $entity->setHexColor();
        }
    }

    public function preUpdate (LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof EventTag)
        {
            $entity->setHexColor();
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof EventTag)
        {
            if(count($entity->getEvents()) != 0)
            {
                throw new AccessDeniedException("you cannot remove this Event, it has already Events registered. Deactivate it and create a new one or change all the Tags of its events.");
            }
        }
    }
}