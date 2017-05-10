<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace BK2K\Sitepackage\GeneratorBundle\Form\Extension;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;

/**
 * ButtonTypeIconExtension
 */
class ButtonTypeIconExtension extends AbstractIconExtension
{
    /**
     * @return string
     */
    public function getExtendedType()
    {
        return ButtonType::class;
    }
}
