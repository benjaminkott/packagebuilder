<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Form;

use App\Entity\Package;
use App\Entity\Package\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * PackageType
 */
class PackageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action'])
            ->add('basePackage', ChoiceType::class, [
                'choices' => [
                    'Bootstrap Package' => 'bootstrap_package',
                    'Fluid Styled Content' => 'fluid_styled_content'
                ],
                'expanded' => true
            ])
            ->add('title', TextType::class, [
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => 'My Sitepackage'
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => 'Optional description for the use of this sitepackage'
                ]
            ])
            ->add('repositoryUrl', TextType::class, [
                'required' => false,
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => 'https://github.com/username/my_sitepackage'
                ]
            ])
            ->add(
                $builder->create('author', FormType::class, ['data_class' => Author::class])
                    ->add('name', TextType::class, [
                        'attr' => [
                            'autocomplete' => 'off',
                            'placeholder' => 'John Doe'
                        ]
                    ])
                    ->add('email', EmailType::class, [
                        'attr' => [
                            'autocomplete' => 'off',
                            'placeholder' => 'john.doe@example.com'
                        ]
                    ])
                    ->add('company', TextType::class, [
                        'attr' => [
                            'autocomplete' => 'off',
                            'placeholder' => 'Company Inc.'
                        ]
                    ])
                    ->add('homepage', TextType::class, [
                        'attr' => [
                            'autocomplete' => 'off',
                            'placeholder' => 'https://www.example.com'
                        ]
                    ])
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Package::class,
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'packageForm';
    }
}
