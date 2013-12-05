<?php

namespace Kpacha\Tests\BundleCompactor\Helpers;

use Kpacha\BundleCompactor\Helpers\CompactedWriter;
use Kpacha\BundleCompactor\CompactorConstants;

/**
 * Description of CompactedWriterTest
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class CompactedWriterTest extends \PHPUnit_Framework_TestCase
{

    const COMPACTED_FILE_CONTENT = "<?php\n\n";
    const FIXTURE_DIR = '/../../../../fixtures';
    const INSTANCE_NAME = 'test';

    private $fixturePath;

    public function setUp()
    {
        $this->fixturePath = realpath(__DIR__ . self::FIXTURE_DIR). '/writeTest';
        $this->cleanFixture();
    }

    public function tearDown()
    {
        $this->cleanFixture();
    }

    public function testInit()
    {
        $this->doTestInit($this->fixturePath);
    }

    public function testInitCreatesAFolder()
    {
        $this->doTestInit($this->fixturePath . '/subfolder');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInitUnreachableFolder()
    {
        $this->initWriter('/unreachablePath');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInitBadPath()
    {
        $this->initWriter('');
    }

    private function cleanFixture()
    {
        if (file_exists($this->fixturePath) || is_dir($this->fixturePath)) {
            exec("rm -rf $this->fixturePath/*");
        }
    }

    private function doTestInit($path)
    {
        $this->initWriter($path);
        $this->assertEquals(self::COMPACTED_FILE_CONTENT,
                file_get_contents($path . '/' . self::INSTANCE_NAME . CompactorConstants::COMPACTED_SOURCE_EXTENSION));
    }

    private function initWriter($path)
    {
        $writer = new CompactedWriter($path, self::INSTANCE_NAME, CompactorConstants::COMPACTED_SOURCE_EXTENSION);
        $writer->init();
    }

}
