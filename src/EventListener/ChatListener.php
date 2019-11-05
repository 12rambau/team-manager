<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\ChatMessage;

class ChatListener
{
    public function prePersist (LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof ChatMessage)
        {
            $entity->setDate(new \DateTime());
            #send a message to the administrator
        }
    }

    public function preUpdate (LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof ChatMessage)
        {
            #send a message to the administrator
        }
    }
}