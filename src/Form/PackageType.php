<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Form;

use App\Entity\Package;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $extended = $options['data']->getExtended();

        $builder
            ->setAction($options['action'])
            ->add('typo3Version', ChoiceType::class, [
                'label' => 'TYPO3 Version',
                'choices' => [
                    '10.4' => 10004000,
                    '9.5' => 9005000,
                    '8.7' => 8007000
                ],
                'expanded' => true
            ])
            ->add('basePackage', ChoiceType::class, [
                'label' => 'Base Package',
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
                'label' => 'Repository URL',
                'required' => false,
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => 'https://github.com/username/my_sitepackage'
                ]
            ]);

        if ($extended) {
            $builder
                ->add('vendorName', TextType::class, [
                    'label' => 'PHP Vendor Name',
                    'required' => false,
                    'attr' => [
                        'autocomplete' => 'on',
                        'placeholder' => 'BK2K',
                    ],
                ])
                ->add('packageName', TextType::class, [
                    'label' => 'PHP Package Name',
                    'required' => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'my_sitepackage',
                    ],
                ])
                ->add('vendorNameAlternative', TextType::class, [
                    'label' => 'Composer Vendor Name',
                    'required' => false,
                    'attr' => [
                        'autocomplete' => 'on',
                        'placeholder' => 'BK2K',
                    ],
                ])
                ->add('packageNameAlternative', TextType::class, [
                    'label' => 'Composer Package Name',
                    'required' => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'my_sitepackage',
                    ],
                ])
                ->add('extensionKey', TextType::class, [
                    'label' => 'Extension Key',
                    'required' => false,
                    'attr' => [
                        'autocomplete' => 'off',
                        'placeholder' => 'my_sitepackage',
                    ],
                ]);
        }

        if (!$extended) {
            $builder->add(
                'extended',
                SubmitType::class,
                [
                    'label' => 'Extended Configuration',
                    'icon' => 'pencil',
                    'attr' => ['class' => 'btn-secondary'],
                    'validate' => false
                ]
            );
        }

        $builder->add('author', AuthorType::class);
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
