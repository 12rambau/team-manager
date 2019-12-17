<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\BlogPost;

class NewsListener
{
    public function prePersist (LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof BlogPost)
        {
            # TODO send a message to the administrator
        }
    }

    public function preUpdate (LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof BlogPost)
        {
            $entity->setEditDate();
            # TODO send a message to the administrator
        }
    }
}