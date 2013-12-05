<?php

namespace Kpacha\BundleCompactor\Helpers\ClassMapGenerators;

use TokenReflection\Broker;
use TokenReflection\Exception\FileProcessingException;
use Kpacha\BundleCompactor\Helpers\AbstractClassMapGenerator;

/**
 * Description of TokenReflectorClassMapGenerator
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class TokenReflectorClassMapGenerator extends AbstractClassMapGenerator
{

    /**
     * @var Broker
     */
    private $reflectorBroker;

    public function __construct($destination, $bundleName, $extension, $compactedExtension, Broker $reflectorBroker)
    {
        parent::__construct($destination, $bundleName, $extension, $compactedExtension);
        $this->reflectorBroker = $reflectorBroker;
    }

    protected function checkLoadedClasses($file)
    {
        return $this->getClassesFromNamespaces($this->getNamespacesFromFile($file));
    }

    protected function getNamespacesFromFile($file)
    {
        try {
            return $this->reflectorBroker->processFile($file->getRealpath(), true)->getNamespaces();
        } catch (FileProcessingException $e) {
            return array();
        }
    }

    protected function getClassesFromNamespaces($namespaces)
    {
        $classes = array();
        foreach ($namespaces as $namespace) {
            foreach ($namespace->getClasses() as $class) {
                $classes[] = $class->getName();
            }
        }
        return $classes;
    }

}
