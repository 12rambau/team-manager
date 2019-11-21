<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use App\Entity\Location;

class LocationListener
{
    public function preRemove (LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof Location)
        {
            //forbid the delation of contact
            if($entity->getTag() == "contact"){
                throw new AccessDeniedException('You cannot delete the contact location !');
            }
            
        }
    }
}