<?php

namespace Kpacha\BundleCompactor\Helpers;

use Kpacha\BundleCompactor\CompactorConstants;

/**
 * Description of AbstractClassMapGenerator
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class AbstractClassMapGeneratorTest extends \PHPUnit_Framework_TestCase
{

    const FIXTURE_DIR = '/../../../../fixtures';

    private $generator;
    private $destinationPath;

    public function setUp()
    {
        $this->destinationFile = '/test' . CompactorConstants::CLASSMAP_EXTENSION;
        $this->compactedFile = '/test' . CompactorConstants::COMPACTED_SOURCE_EXTENSION;
        $this->destinationPath = realpath(__DIR__ . self::FIXTURE_DIR) . $this->destinationFile;
        exec("touch $this->destinationPath");

        $this->generator = $this->getMock(
                'Kpacha\BundleCompactor\Helpers\AbstractClassMapGenerator', array('checkLoadedClasses'),
                array(
            dirname($this->destinationPath), 'test',
            CompactorConstants::CLASSMAP_EXTENSION, CompactorConstants::COMPACTED_SOURCE_EXTENSION
                )
        );
        $this->generator->expects($this->once())->method('checkLoadedClasses')->will($this->returnValue(array('Class')));
        $this->assertNull($this->generator->init());
    }

    public function tearDown()
    {
        unlink($this->destinationPath);
    }

    public function testAdd()
    {
        $this->assertEquals(1, $this->addFileToClassMapGenerator());
    }

    public function testGenerate()
    {
        $this->addFileToClassMapGenerator();
        $this->generator->generate();
        $content = "<?php\n\n\$compactorDir = realpath(dirname(__FILE__));\n\nreturn array(\n    'Class' => \$compactorDir . '$this->compactedFile',\n);\n";
        $this->assertEquals($content, file_get_contents($this->destinationPath));
    }

    private function addFileToClassMapGenerator()
    {
        return $this->generator->add('classFilePath');
    }

}