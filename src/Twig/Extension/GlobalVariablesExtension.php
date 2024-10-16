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
use Twig\Extension\GlobalsInterface;

class GlobalVariablesExtension extends AbstractExtension implements GlobalsInterface
{
    public function getGlobals(): array
    {
        return [
            'template' => [
                'author' => [
                    'name' => 'Benjamin Kott',
                    'email' => 'benjamin.kott@outlook.com',
                    'hash' => md5('benjamin.kott@outlook.com'),
                    'twitter' => 'benjaminkott',
                    'github' => 'benjaminkott',
                    'description' => 'Benjamin is Frontend-Developer at <a class="font-weight-bold" href="https://www.typo3.com" target="_blank">TYPO3</a> and worked on projects based on TYPO3 CMS from mid- to enterprise size. Since 2014 his <a class="font-weight-bold" href="https://github.com/benjaminkott/bootstrap_package" target="_blank">Bootstrap Package</a> is used as codebase for the official TYPO3 CMS Introduction Package with the goal to provide an extensive best practice example on how to create websites efficiently with TYPO3 CMS.',
                ],
            ],
        ];
    }

    public function getName(): string
    {
        return 'template_global_variable';
    }
}
