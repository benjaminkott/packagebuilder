<?php declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Form;

use App\Entity\Package;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PackageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action'])
            ->add('basePackage', ChoiceType::class, [
                'label' => 'Base Package',
                'choices' => [
                    'Bootstrap Package' => 'bootstrap_package',
                    'Fluid Styled Content' => 'fluid_styled_content',
                ],
                'expanded' => true,
            ])
            ->add('typo3Version', ChoiceType::class, [
                'label' => 'TYPO3 Version',
                'choices' => [
                    '13.4' => 13.4,
                    '12.4' => 12.4,
                    '11.5' => 11.5,
                    '10.4' => 10.4,
                    '9.5' => 9.5,
                    '8.7' => 8.7,
                ],
                'expanded' => true,
            ])
            ->add('title', TextType::class, [
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => 'My Sitepackage',
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => 'Optional description for the use of this sitepackage',
                ],
            ])
            ->add('repositoryUrl', TextType::class, [
                'label' => 'Repository URL',
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => 'https://github.com/username/my_sitepackage',
                ],
            ])
            ->add('author', AuthorType::class);
    }

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
