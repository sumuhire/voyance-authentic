<?php

namespace App\Form;

use App\Entity\Piatto;
use App\Entity\PiattoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PiattoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'italien',
                TextareaType::class, 
                ['attr' => ['class' => 'form-control form-control form-plat',"placeholder"=>" Piatto in lingua italiana"],"label" => false]
                )
            ->add(
                'francais',
                TextareaType::class, 
                ['attr' => ['class' => 'form-control form-control form-plat',"placeholder"=>"Piatto in lingua francese "],"label" => false]
                )
            ->add(
                'prix',
                TextType::class, 
                ['attr' => ['class' => 'form-control form-control',"placeholder"=>"Prezzo"],"label" => false]
                )
            ->add(
                'prixEntree',
                TextType::class, 
                ['attr' => ['class' => 'form-control form-control',"placeholder"=>"Prezzo grande (Antipasti - Formaggio)"],"label" => false,'required' => false]
                )
            ->add(
                'piattoType',
                EntityType::class, 
                [
                    'class' => PiattoType::class,
                    'choice_label' => 'label',
                    'attr' => ['class' => 'form-control form-control'],
                    'label' => false,
                    
                ])
        ;

        if ($options['standalone']) {
            $builder->add(
                'submit',
                SubmitType::class,
                ['attr' => ['class' => "btn btn-primary btn-lg"], 'label' => 'Salvo']
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
