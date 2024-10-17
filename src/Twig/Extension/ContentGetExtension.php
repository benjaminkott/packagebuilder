<?php

declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ContentGetExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('contentget', $this->fileGetContents(...), ['is_safe' => ['html']]),
        ];
    }

    public function fileGetContents($file): string
    {
        return file_get_contents($file);
    }

    public function getName(): string
    {
        return 'contentget';
    }
}
