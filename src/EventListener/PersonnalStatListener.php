<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\PersonnalStat;

class PersonnalStatListener
{
    public function prePersist (LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof PersonnalStat)
        {
            ($entity->getTimer()) ? $entity->setValue(null):$entity->setTime(null);
            #TODO send a message to the administrator
        }
    }

    public function preUpdate (LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof PersonnalStat)
        {
            ($entity->getTimer()) ? $entity->setValue(null):$entity->setTime(null);
            #TODO send a message to the administrator
        }

    }
}