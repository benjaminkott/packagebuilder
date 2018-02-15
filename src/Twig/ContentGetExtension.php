<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Twig;

use Twig_Extension;
use Twig_SimpleFunction;

/**
 * ContentExtension.
 **/
class ContentGetExtension extends Twig_Extension
{
    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('contentget', [$this, 'fileGetContents'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @return string
     */
    public function fileGetContents($file)
    {
        return file_get_contents($file);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'contentget';
    }
}
