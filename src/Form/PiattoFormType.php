<?php

namespace App\Form;

use App\Entity\Piatto;
use App\Entity\PiattoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PiattoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'italien',
                TextType::class, 
                ['attr' => ['class' => 'form-control form-control'],"label"=>" Plat - Italien"]
                )
            ->add(
                'francais',
                TextType::class, 
                ['attr' => ['class' => 'form-control form-control'],"label"=>"Plat - Français "]
                )
            ->add(
                'prix',
                TextType::class, 
                ['attr' => ['class' => 'form-control form-control'],"label"=>"Prix"]
                )
            ->add(
                'piattoType',
                EntityType::class, 
                [
                    'class' => PiattoType::class,
                    'choice_label' => 'label',
                    'attr' => ['class' => 'form-control form-control']
                ])
        ;

        if ($options['standalone']) {
            $builder->add(
                'submit',
                SubmitType::class,
                ['attr' => ['class' => "btn btn-primary btn-lg"], 'label' => 'Save']
            );
        }    
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Piatto::class,
            'standalone' => false
        ]);
    }
}
