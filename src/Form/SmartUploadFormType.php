<?php

namespace App\Form;

use App\Entity\SmartUpload;
use App\Form\SmartIssuanceFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class SmartUploadFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add(
            'classYearStart',
            DateType::class,
            [
                'widget' => 'choice',
                'label' => 'Starting year', 
                'years' => range(2000,2020),
                'placeholder' => array(
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                )
            ]
            )
        ->add(
            'classYearEnd',
            DateType::class,
            [
                'widget' => 'choice',
                'label' => 'Ending year', 
                'years' => range(2000,2020),
                'placeholder' => array(
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                )
                
            ]
            )
            ->add(
                'excelFile',
                VichImageType::class,  
                [
                    'allow_delete' => true,
                    'attr' => [
                        'class' => 'dropzone'
                        ]
                ]
                )
                
            ->add('submit', SubmitType::class,
            [
                'attr' => ['class' => 'btn btn-outline-primary btn-block'],
                'label' => 'Create a class'
                ]
            )
            ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SmartUpload::class,
        ]);
    }
}
