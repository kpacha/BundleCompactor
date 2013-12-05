<?php

namespace Kpacha\BundleCompactor\Helpers\ClassMapGenerators;

use Kpacha\BundleCompactor\Helpers\AbstractClassMapGenerator;

/**
 * Description of DeclaredParserClassMapGenerator
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class DeclaredParserClassMapGenerator extends AbstractClassMapGenerator
{
    private static $bannedPatterns = array('/\\\Finder\\\/', '/^\\\/');

    public function init()
    {
        $this->doCheckLoadedClasses();
    }

    protected function checkLoadedClasses($file)
    {
        $fileName = $file->getRealpath();
        include_once $fileName;
        return $this->doCheckLoadedClasses();
    }

    private function doCheckLoadedClasses()
    {
        $classes = get_declared_classes();
        foreach (self::$bannedPatterns as $bannedPattern) {
            $classes = preg_grep($bannedPattern, $classes, PREG_GREP_INVERT);
        }
        $diff = array_diff($classes, $this->loadedClasses);
        $this->loadedClasses = $classes;
        return $diff;
    }

}
