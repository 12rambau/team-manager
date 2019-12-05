<?php

namespace App\EventListener;

use App\Entity\FeatureTag;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class FeatureTagListener
{

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof FeatureTag)
        {
            if(count($entity->getPlayers()) != 0)
            {
                throw new AccessDeniedException("you cannot remove this Feature Tag, it has already features registered. Deactivate it and create a new one or change all the Tags of its features.");
            }
        }
    }
}