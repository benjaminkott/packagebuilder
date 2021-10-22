<?php declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * AbstractIconExtension.
 */
abstract class AbstractIconExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('icon', $options['icon']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['icon'] = $options['icon'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['icon' => null]);
        $resolver->setDefined(['icon']);
    }
}
