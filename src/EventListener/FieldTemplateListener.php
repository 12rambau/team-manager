<?php

namespace App\EventListener;

use App\Entity\FieldTemplate;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class FieldTemplateListener
{
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof FieldTemplate)
        {
            if (count($entity->getFields()))
                throw new AccessDeniedException("this template has already field it cannot be changed");
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof FieldTemplate)
        {
            if(count($entity->getFields())){

                $em = $args->getObjectManager();
                $em->detach($entity);

                throw new AccessDeniedException('this template has already fields it cannot be removed');
            }
        }
    }
}