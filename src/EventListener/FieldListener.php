<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\FieldTemplate;
use App\Entity\Field;

class FieldListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        
        $entity = $args->getObject();

        if ($entity instanceof FieldTemplate)
        {
            $entity->setUpdateAt();
        }
        if ($entity instanceof Field)
        {
            $entity->setUpdateAt();
        }

        return;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof FieldTemplate)
        {
            $entity->setUpdateAt();
        }
        if($entity instanceof Field)
        {
            $entity->setUpdateAt();
        }
    }
}