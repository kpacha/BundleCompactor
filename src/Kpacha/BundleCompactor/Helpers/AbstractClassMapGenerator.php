<?php

namespace Kpacha\BundleCompactor\Helpers;

/**
 * Description of AbstractClassMapGenerator
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
abstract class AbstractClassMapGenerator
{

    protected $loadedClasses = array();
    private $classMap = array();
    private $destination;
    private $bundleName;
    private $extension;
    private $compactedExtension;

    public function __construct($destination, $bundleName, $extension, $compactedExtension)
    {
        $this->destination = $destination;
        $this->bundleName = $bundleName;
        $this->extension = $extension;
        $this->compactedExtension = $compactedExtension;
    }

    public function init()
    {
        
    }

    protected abstract function checkLoadedClasses($file);

    public function add($file)
    {
        $classesToAdd = $this->checkLoadedClasses($file);
        $this->registerClasses($classesToAdd);
        return count($classesToAdd);
    }

    private function registerClasses($classes)
    {
        $classes = (is_array($classes)) ? $classes : array($classes);
        $this->classMap = array_merge($this->classMap, $classes);
    }

    public function generate()
    {
        $this->write("<?php\n\n", 0);
        $this->write("\$compactorDir = realpath(dirname(__FILE__));\n\n");
        $this->write("return array(\n");
        $file = $this->getCompactedFileName();
        foreach ($this->classMap as $className) {
            $this->write("    '$className' => \$compactorDir . '/$file',\n");
        }
        $this->write(");\n");
    }

    private function write($content, $flag = FILE_APPEND)
    {
        return file_put_contents($this->getClassMapFilePath(), $content, $flag);
    }

    private function getClassMapFilePath()
    {
        return $this->destination . '/' . $this->bundleName . $this->extension;
    }

    private function getCompactedFileName()
    {
        return $this->bundleName . $this->compactedExtension;
    }

}
