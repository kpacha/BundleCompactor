<?php

namespace Kpacha\BundleCompactor;

use Kpacha\BundleCompactor\Helpers\CompactedWriter;
use Kpacha\BundleCompactor\Helpers\SourceReader;
use Kpacha\BundleCompactor\Helpers\AbstractClassMapGenerator;

/**
 * Description of Compactor
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class Compactor
{

    /**
     * @var CompactedWriter
     */
    private $writer;

    /**
     * @var SourceReader
     */
    private $reader;

    /**
     * @var AbstractClassMapGenerator
     */
    private $classMapGenerator;

    public function __construct(SourceReader $sourceFolder, CompactedWriter $writer,
            AbstractClassMapGenerator $classMapGenerator)
    {
        $this->reader = $sourceFolder;
        $this->writer = $writer;
        $this->classMapGenerator = $classMapGenerator;
    }

    public function compact()
    {
        $this->init();
        $this->compactSources();
        $this->generateClassMap();
    }

    private function init()
    {
        $this->writer->init();
        $this->classMapGenerator->init();
    }

    private function compactSources()
    {
        $fileIterator = $this->reader->getIterator();
        foreach ($fileIterator as $file) {
            $this->writer->write($this->reader->getContents($file));
            $this->classMapGenerator->add($file);
        }
    }

    private function generateClassMap()
    {
        $this->classMapGenerator->generate();
    }

}