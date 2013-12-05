<?php

namespace Kpacha\BundleCompactor;

use Kpacha\BundleCompactor\Helpers\SourceReader;
use Kpacha\BundleCompactor\Helpers\CompactedWriter;
use Kpacha\BundleCompactor\Helpers\ClassMapGenerators\TokenReflectorClassMapGenerator;
use TokenReflection\Broker;
use \TokenReflection\Broker\Backend\Memory;

/**
 * Description of CompactorBuilder
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class CompactorBuilder
{

    const CLASSMAP_GENERATOR_SPACENAME = 'Kpacha\BundleCompactor\Helpers\ClassMapGenerators';
    const CLASSMAP_GENERATOR_SUFFIX = 'ClassMapGenerator';

    /**
     * @var String
     */
    private $bundleName;

    /**
     * @var String
     */
    private $sourcePath;

    /**
     * @var String
     */
    private $destination;

    /**
     * @var String
     */
    private $compactedExtension = CompactorConstants::COMPACTED_SOURCE_EXTENSION;

    /**
     * @var String
     */
    private $classmapExtension = CompactorConstants::CLASSMAP_EXTENSION;

    /**
     * @var Compactor
     */
    private $compactor;

    /**
     * @var Kpacha\BundleCompactor\Helpers\SourceReader
     */
    private $reader;

    /**
     * @var Kpacha\BundleCompactor\Helpers\CompactedWriter
     */
    private $writer;

    /**
     * @var Kpacha\BundleCompactor\Helpers\AbstractClassMapGenerator
     */
    private $clasMapGenerator;

    /**
     * @var TokenReflection\Broker
     */
    private $broker;

    public function setReader($reader)
    {
        $this->reader = $reader;
        return $this;
    }

    public function setWriter($writer)
    {
        $this->writer = $writer;
        return $this;
    }

    public function setClassMapGenerator($clasMapGenerator)
    {
        $this->clasMapGenerator = $clasMapGenerator;
        return $this;
    }

    public function setClassMapGeneratorStrategy($classMapGeneratorStrategyName)
    {
        $classMapGeneratorClass = self::CLASSMAP_GENERATOR_SPACENAME . '\\' . $classMapGeneratorStrategyName . self::CLASSMAP_GENERATOR_SUFFIX;
        if (!$classMapGeneratorStrategyName || $classMapGeneratorClass == 'TokenReflector') {
            $this->clasMapGenerator = new TokenReflectorClassMapGenerator(
                            $this->getDestination(), $this->getBundleName(), $this->classmapExtension,
                            $this->compactedExtension, $this->getBroker()
            );
        } else {
            $this->clasMapGenerator = new $classMapGeneratorClass(
                            $this->getDestination(), $this->getBundleName(), $this->classmapExtension, $this->compactedExtension
            );
        }
        return $this;
    }

    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }

    public function setBundleName($bundleName)
    {
        $this->bundleName = $bundleName;
        return $this;
    }

    public function setSourcePath($sourcePath)
    {
        $this->sourcePath = $sourcePath;
        return $this;
    }

    public function setBroker($broker)
    {
        $this->broker = $broker;
        return $this;
    }

    public function build()
    {
        $this->compactor = new Compactor($this->getReader(), $this->getWriter(), $this->getClassMapGenerator());
        return $this->compactor;
    }

    private function getReader()
    {
        if (!$this->reader) {
            $this->reader = new SourceReader($this->getSourcePath());
        }
        return $this->reader;
    }

    private function getWriter()
    {
        if (!$this->writer) {
            $this->writer = new CompactedWriter(
                            $this->getDestination(), $this->getBundleName(), $this->compactedExtension
            );
        }
        return $this->writer;
    }

    private function getClassMapGenerator()
    {
        if (!$this->clasMapGenerator) {
            $this->setClassMapGeneratorStrategy(null);
        }
        return $this->clasMapGenerator;
    }

    private function getBroker()
    {
        if (!$this->broker) {
            $this->broker = new Broker(new Memory);
        }
        return $this->broker;
    }

    private function getDestination()
    {
        if (!$this->destination) {
            throw new \InvalidArgumentException("Destionation is required!");
        }
        return $this->destination;
    }

    private function getBundleName()
    {
        if (!$this->bundleName) {
            throw new \InvalidArgumentException("Bundle Name is required!");
        }
        return $this->bundleName;
    }

    private function getSourcePath()
    {
        if (!$this->sourcePath) {
            throw new \InvalidArgumentException("Source Path is required!");
        }
        return $this->sourcePath;
    }

    public function getCompactor()
    {
        return $this->compactor;
    }

}
