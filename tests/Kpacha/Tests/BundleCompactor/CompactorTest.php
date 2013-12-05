<?php

namespace Kpacha\Tests\BundleCompactor;

use Kpacha\BundleCompactor\Compactor;
use Symfony\Component\Finder\Finder;

/**
 * Description of CompactorTest
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class CompactorTest extends \PHPUnit_Framework_TestCase
{

    private $finder;
    private $totalFiles = 3;

    public function setUp()
    {
        $this->finder = $this->buildFinder();
    }

    public function testCompact()
    {
        $reader = $this->getMock('Kpacha\BundleCompactor\Helpers\SourceReader', array('getIterator', 'getContents'),
                array(), '', false);
        $reader->expects($this->once())->method('getIterator')->will($this->returnValue($this->finder));
        $reader->expects($this->exactly($this->totalFiles))->method('getContents');

        $writer = $this->getMock('Kpacha\BundleCompactor\Helpers\CompactedWriter', array('init', 'write'), array(''),
                '', false);
        $writer->expects($this->once())->method('init');
        $writer->expects($this->exactly($this->totalFiles))->method('write');

        $classMapGenerator = $this->getMock('Kpacha\BundleCompactor\Helpers\AbstractClassMapGenerator',
                array('init', 'checkLoadedClasses', 'add', 'generate'), array(), '', false);
        $classMapGenerator->expects($this->once())->method('init');
        $classMapGenerator->expects($this->exactly($this->totalFiles))->method('add');
        $classMapGenerator->expects($this->once())->method('generate');

        $compactor = new Compactor($reader, $writer, $classMapGenerator);
        $compactor->compact();
    }

    private function buildFinder()
    {
        $finder = new Finder;
        $finder->files()->name('*.php')->in(__DIR__ . '/../../../fixtures');
        return $finder;
    }

}
