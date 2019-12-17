<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Comment;

class CommentListener
{
    public function prePersist (LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof Comment)
        {
            $entity->setPublishDate(new \DateTime());
            #send a message to the administrator
        }
    }
}