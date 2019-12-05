<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\StatTag;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class StatTagListener
{

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof StatTag)
        {
            if(count($entity->getPlayers()) != 0)
            {
                throw new AccessDeniedException("you cannot remove this Stat Tag, it has already Stats registered. Deactivate it and create a new one or change all the Tags of its stats.");
            }
        }
    }
}