<?php

namespace App\Form;

use App\Entity\DegreeTitle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class DegreeTitleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'label',
                TextType::class, 
                ['attr' => ['class' => 'form-control form-control'],"label"=>"Title of degree"]
            )
            ->add(
                'ects',
                IntegerType::class, 
                ['attr' => ['class' => 'form-control form-control'],"label"=>"Ects"]
            )
            ->add(
                'degreeCode',
                TextType::class, 
                ['attr' => ['class' => 'form-control form-control'],"label"=>"Degree Code"]
            )
            ->add(
                'url',
                TextType::class, 
                ['attr' => ['class' => 'form-control form-control'],"label"=>"Url"]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DegreeTitle::class,
        ]);
    }
}
