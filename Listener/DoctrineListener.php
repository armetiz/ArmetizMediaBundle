<?php

namespace Armetiz\MediaBundle\Listener;

use Armetiz\MediaBundle\MediaManager;
use Armetiz\MediaBundle\Entity\MediaInterface;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class DoctrineListener
{
    /**
     *
     * @var Armetiz\MediaBundle\MediaManager
     */
    private $mediaManager;

    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        
        if ($entity instanceof MediaInterface)
        {
            $this->mediaManager->prepareMedia($entity);
        }
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        
        if ($entity instanceof MediaInterface)
        {
            $this->mediaManager->prepareMedia($entity);
                    
            // Hack ? Don't know, that's the behaviour Doctrine 2 seems to want
            // See : http://www.doctrine-project.org/jira/browse/DDC-1020
            $entityManager = $eventArgs->getEntityManager();
            $unitOfWork = $entityManager->getUnitOfWork();
            $unitOfWork->recomputeSingleEntityChangeSet(
                $entityManager->getClassMetadata(get_class($entity)),
                $eventArgs->getEntity()
            );
        }
    }

    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        if ($eventArgs->getEntity() instanceof MediaInterface)
        {
            $this->mediaManager->saveMedia($eventArgs->getEntity());
        }
    }

    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        if ($eventArgs->getEntity() instanceof MediaInterface)
        {
            $this->mediaManager->saveMedia($eventArgs->getEntity());
        }
    }

    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        
        if (!$entity instanceof MediaInterface) 
        {
            return;
        }
        
        $this->mediaManager->deleteMedia($entity);
    }
}
