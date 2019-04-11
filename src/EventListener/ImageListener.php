<?php
 
namespace App\EventListener;
 
use Doctrine\ORM\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use App\Entity\Image;
use App\Services\ImageTransformer;
use Vich\UploaderBundle\Event\Event;
use Doctrine\ORM\EntityManagerInterface;
 
/**
 * ImageListener
 */
class ImageListener
{
    private $cacheManager;
    private $path_image;
    private $orm;
 
    public function __construct(CacheManager $cacheManager, EntityManagerInterface $orm, string $path_image)
    {
        $this->cacheManager = $cacheManager;
        $this->path_image = $path_image;
        $this->orm = $orm;
    }
 
    public function onVichUploaderPreInject(Event $args)
    {
        $entity = $args->getObject();
 
        if (!$entity instanceof Image) {
            return;
        }
 
        $image = $entity->getFileName();
        $entity->setTmpFile($image);
        $this->orm->flush();
 
    }
 
 
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

 
        if (!$entity instanceof Image) {
            return;
        }
        $changeSet = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($entity);
 
        if(!array_key_exists("fileName", $changeSet)){ 
        return;
        }
  
        try {
            $this->cacheManager->remove($this->path_image.'/'.$entity->getTmpFile());
            $this->cacheManager->resolve($this->path_image.'/'.$entity->getFileName(), null);
 
        } catch (\Exception $e) {
 
        }
 
    }
 
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
 
        if (!$entity instanceof Image) {
            return;
        }
 
        $target = $this->path_image.'/'.$entity->getFileName();
        try {
            $this->cacheManager->remove($target);
        } catch (\Exception $e) {
 
        }
    }
 
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
 
        if (!$entity instanceof Image) {
            return;
        }
        $file = $this->path_image.'/'.$entity->getFileName();
    }
}