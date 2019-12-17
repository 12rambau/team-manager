<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\FieldTemplate;
use App\Entity\Field;

class FieldListener
{
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof Field)
        {
            $entity->setUpdateAt();
        }
    }
}