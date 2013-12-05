<?php

namespace Kpacha\Tests\BundleCompactor\Helpers;

use Kpacha\BundleCompactor\Helpers\SourceReader;

/**
 * Description of SourceReaderTest
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class SourceReaderTest extends \PHPUnit_Framework_TestCase
{

    const FIXTURE_DIR = '/../../../../fixtures';

    private $subject;

    public function setUp()
    {
        $this->subject = new SourceReader(__DIR__ . self::FIXTURE_DIR);
    }

    public function testGetIterator()
    {
        $iterator = $this->subject->getIterator();
        $this->assertInstanceOf('Symfony\Component\Finder\Finder', $iterator);
        $this->assertEquals(3, $iterator->count());
        $files = array();
        foreach ($iterator as $file){
            $files[] = $file->getRealpath();
        }
    }

    public function testGetContent()
    {
        $finder = $this->subject->getIterator()->name('test.php');
        $content = '';
        foreach ($finder as $file) {
            $content .= $this->subject->getContents($file);
        }
        $this->assertEquals("\n\necho 'test';\n\n", $content);
    }

}
