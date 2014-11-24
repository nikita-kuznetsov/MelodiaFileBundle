<?php

namespace Melodia\FileBundle\Utils;

use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DirectoryNamer implements DirectoryNamerInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function directoryName($object, PropertyMapping $mapping)
    {
        return md5($object->getUploadedAt()->getTimestamp() . $this->container->getParameter('secret'));
    }
}