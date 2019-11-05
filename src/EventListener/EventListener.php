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
            $entity->getResult()->getFiles()->setName("result_".$entity->getSlug());
        }

        return;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof Event)
        {
            $entity->setColor();
            $entity->getResult()->getFiles()->setName("result_".$entity->getSlug());
        }
    }
}