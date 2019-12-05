<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\PlayerTag;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class PlayerTagListener
{

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof PlayerTag)
        {
            if(count($entity->getPlayers()) != 0)
            {
                throw new AccessDeniedException("you cannot remove this PLayer Tag, it has already PLayers registered. Deactivate it and create a new one or change all the Tags of its players.");
            }
        }
    }
}