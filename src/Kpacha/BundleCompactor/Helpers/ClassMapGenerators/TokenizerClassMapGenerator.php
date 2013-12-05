<?php

namespace Kpacha\BundleCompactor\Helpers\ClassMapGenerators;

use Kpacha\BundleCompactor\Helpers\AbstractClassMapGenerator;

/**
 * Description of TokenizerClassMapGenerator
 *
 * @author Kpacha <kpacha666@gmail.com>
 */
class TokenizerClassMapGenerator extends AbstractClassMapGenerator
{

    protected function checkLoadedClasses($file)
    {
        return array_keys($this->getClassesFromFile($file));
    }

    protected function getClassesFromFile($file)
    {
        return $this->getClassesFromTokens($this->getTokensFromFile($file));
    }

    protected function getTokensFromFile($file)
    {
        return token_get_all($file->getContents());
    }

    protected function getClassesFromTokens(array $tokens)
    {
        //TODO get the namespaces!!!
        $class = array();
        $totalTokens = count($tokens);
        for ($key = 0; $key < $totalTokens; $key++) {
            if ($tokens[$key][0] === T_CLASS) {
                for ($j = $key + 1; $j < $totalTokens; $j++) {
                    if ($tokens[$j] === '{') {
                        $class[$tokens[$key + 2][1]] = true;
                    }
                }
            }
        }
        return $class;
    }

}
