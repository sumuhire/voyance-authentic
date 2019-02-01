<?php

namespace App\Form;

use App\Entity\School;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SchoolSettingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                ["label" => "Name", 'attr' => ['class' => 'form-control text-left']]
                )
            ->add(
                'type',
                TextType::class,
                ["label" => "Type", 'attr' => ['class' => 'form-control text-left']]
                )
            ->add(
                'establishmentDate',
                DateType::class,
                [
                    'widget' => 'choice', 
                    "label" => "Establishment date", 
                    'years' => range(1600,2020)]
                )
            ->add(
                'location',
                TextType::class,
                ["label" => "Location", 'attr' => ['class' => 'form-control text-left']]
                )
            ->add(
                'website',
                UrlType::class,
                ["label" => "Website", 'attr' => ['class' => 'form-control text-left']]
                )
            // ->add(
            //     'motto',
            //     TextareaType::class,
            //     ["label" => "Motto", 'attr' => ['class' => 'form-control text-left']]
            //     )
            ->add(
                'aboutUs',
                TextareaType::class,
                ["label" => "About us", 'attr' => ['class' => 'form-control text-left']]
                )
            ->add(
                'logo',
                FileType::class,
                ["data_class" => null, "disabled" => true, "attr" => ["class" => "d-none"], 'label' => ' ']
                )

    ;

    if (!$options['standalone']) {
        $builder->add(
            'submit',
            SubmitType::class,
            ['attr' => ['class' => 'btn btn-warning btn-block']]
        );
    }
}


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => School::class,
            'standalone' => false
        ]);
    }
}
