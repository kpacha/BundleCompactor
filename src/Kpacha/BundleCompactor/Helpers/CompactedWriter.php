<?php

namespace Kpacha\BundleCompactor\Helpers;

/**
 * Description of CompactedWriter
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class CompactedWriter
{

    /**
     * @var String
     */
    private $destinationFolder;
    private $destinationFile;

    public function __construct($destination, $bundleName, $extension)
    {
        $this->destinationFolder = $destination;
        $this->destinationFile = $this->destinationFolder . '/' . $bundleName . $extension;
    }
    
    public function init()
    {
        $this->initDestinationFolder();
        $this->prepareDestinationFile();
    }

    private function prepareDestinationFile()
    {
        $this->write("<?php\n\n", 0);
    }

    private function initDestinationFolder()
    {
        if (!is_dir($this->destinationFolder) && !file_exists($this->destinationFolder)) {
            if (false === @mkdir($this->destinationFolder, 0777, true)) {
                throw new \RuntimeException(sprintf('Could not create directory "%s".', $this->destinationFolder));
            }
        }
    }

    public function write($content, $flag = FILE_APPEND)
    {
        return @file_put_contents($this->destinationFile, $content, $flag);
    }
    
}
