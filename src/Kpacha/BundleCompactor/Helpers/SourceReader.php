<?php

namespace Kpacha\BundleCompactor\Helpers;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Description of SourceReader
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class SourceReader implements \IteratorAggregate
{

    private static $bannedCombinations = array('<?php', '<?', '?>');

    /**
     * @var Finder
     */
    private $finder;

    public function __construct($sourcePath)
    {
        $this->finder = new Finder;
        $this->finder
                ->files()
                ->name('*.php')
                ->in($sourcePath);
    }

    public function getContents(SplFileInfo $file)
    {
        return $this->removeBannedCombinations($file->getContents());
    }

    private function removeBannedCombinations($content)
    {
        foreach (self::$bannedCombinations as $bannedCombination) {
            $content = str_replace($bannedCombination, '', $content);
        }
        return $content;
    }

    public function getIterator()
    {
        return $this->finder;
    }

}
