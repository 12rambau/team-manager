<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Participation;

class UpdateTime
{
    public function preUpdate(LifecycleEventArgs $args)
    {
        
        $entity = $args->getObject();

        if ($entity instanceof Participation) { 

            $entity->setLastUpdate(new \DateTime());
        }

        return;
    }
}