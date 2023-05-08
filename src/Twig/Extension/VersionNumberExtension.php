<?php

declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * VersionNumberExtension.
 **/
class VersionNumberExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new TwigFilter(
                'version',
                $this->versionFilter(...)
            ),
        ];
    }

    /**
     * @return int
     */
    public function versionFilter($version, $positions = 3)
    {
        $versionInt = (int) $version;
        $versionString = str_pad((string) $versionInt, 9, '0', STR_PAD_LEFT);
        $parts = [
            substr($versionString, 0, 3),
            substr($versionString, 3, 3),
            substr($versionString, 6, 3),
        ];

        return match ($positions) {
            1 => (int) $parts[0],
            2 => (int) $parts[0] . '.' . (int) $parts[1],
            default => (int) $parts[0] . '.' . (int) $parts[1] . '.' . (int) $parts[2],
        };
    }
}
