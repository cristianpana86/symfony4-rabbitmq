<?php
/**
 * @author: Cristian Pana
 * Date: 15.11.2020
 */

namespace CPANA\App\EventListener;

use CPANA\App\Entity\Image;
use CPANA\App\Message\ImageCachingMessage;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;

class ImageDoctrineListener
{
    protected $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * When persisting images to DB add also new task to queue in order to download the file to local cache
     * Docs: https://symfony.com/blog/new-in-symfony-4-4-simpler-event-listeners#invokable-doctrine-entity-listeners
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // only act on Image entities
        if (!$entity instanceof Image) {
            return;
        }
        // Image id should be available in postPersist event
        $message = new ImageCachingMessage($entity->getId(), $entity->getUrl());
        $this->bus->dispatch($message);

    }
}