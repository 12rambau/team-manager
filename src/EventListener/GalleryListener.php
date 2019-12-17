<?php

namespace App\EventListener;

use App\Entity\Gallery;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class GalleryListener
{
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Gallery) {
            //if (
             //   $entity->getBlogPost() != null
              //  &&
              //  $entity->getName() != $entity->getBlogPost()->getSlug()
            //) {
                throw new AccessDeniedException('Change the name of a blogPost gallery is forbidden; '.$entity->getName().'is equal to '.$entity->getBlogPost()->getSlug());
            //}
        }
    }
}
