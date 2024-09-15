<?php

namespace App\Form;

use App\Entity\Animal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\File;

class Animal1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('age')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Chien' => 'chien',
                    'Cheval' => 'cheval',
                    'Brebis' => 'brebis',
                    'Cochon' => 'cochon',
                ],
                'label' => 'Type',
            ])
            ->add('race', ChoiceType::class, [
                'choices' => [
                    'Labrador' => 'labrador',
                    'Frison' => 'frison',
                    'Pottok' => 'pottok',
                    'Irish Cob' => 'irish cob',
                    'Mérinos' => 'mérinos',
                    'Solognotes' => 'solognotes',
                ],
                'label' => 'Race',
            ])
            ->add('description')
            ->add('photos', FileType::class, [
                'label' => 'Photos (JPEG, PNG files)',
                'multiple' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '4024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPEG or PNG)',
                    ])
                ],
            ])
            ->add('status')
            ->add('price')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
