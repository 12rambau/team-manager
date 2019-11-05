<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\EventTag;

class EventTagListener
{
    public function prePersist (LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof EventTag)
        {
            $entity->setHexColor();
            #send a message to the administrator
        }
    }

    public function preUpdate (LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof EventTag)
        {
            $entity->setHexColor();
            #send a message to the administrator
        }
    }
}