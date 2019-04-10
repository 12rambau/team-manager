<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Event;

class EventListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        
        $entity = $args->getObject();

        if ($entity instanceof Event)
        {
            $entity->setColor();
        }

        return;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof Event)
        {
            $entity->setColor();
        }
    }
}