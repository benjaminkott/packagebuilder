<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Form;

use App\Entity\Package\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'autocomplete' => 'on',
                    'placeholder' => 'John Doe',
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'autocomplete' => 'on',
                    'placeholder' => 'john.doe@example.com',
                ],
            ])
            ->add('company', TextType::class, [
                'attr' => [
                    'autocomplete' => 'on',
                    'placeholder' => 'Company Inc.',
                ],
            ])
            ->add('homepage', TextType::class, [
                'attr' => [
                    'autocomplete' => 'on',
                    'placeholder' => 'https://www.example.com',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
