<?php declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Form\Extension;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class ButtonTypeIconExtension extends AbstractIconExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [ButtonType::class];
    }
}
