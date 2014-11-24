<?php

namespace Melodia\FileBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Melodia\FileBundle\Entity\File;

class FileSavingListener
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof File) {
            $fileUri = $this->container->get('vich_uploader.templating.helper.uploader_helper')->asset($entity, 'file');
            $entity->setFileUri($fileUri);

            $entityManager = $args->getEntityManager();
            $entityManager->persist($entity);
            $entityManager->flush();
        }
    }
}