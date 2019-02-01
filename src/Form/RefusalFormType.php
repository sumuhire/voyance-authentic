<?php

namespace App\Form;

use App\Entity\GraduateUserInvite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RefusalFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'acceptance',
                ChoiceType::class,
                [
                    'label' => 'Do you want to register on Nimble?',

                    'choices' => [
                            'Yes' => 'accepted',
                            'No' => 'declined',

                    ]
                            
                ]
                )
            
          
        ;

        $builder->add(
            'submit', 
            SubmitType::class, 
            ['attr' => ['class' => 'btn-warning btn-block']]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GraduateUserInvite::class,
        ]);
    }
}
