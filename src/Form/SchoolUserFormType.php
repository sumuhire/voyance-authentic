<?php

namespace App\Form;

use App\Entity\Faculty;
use App\Entity\SchoolUser;
use App\Form\SchoolUserFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SchoolUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder         
            ->add(
                'schoolPosition',
                TextType::class, 
                [
                    "label" => "work position", 
                    'attr' => ['class' => 'form-control text-left']
                ]
                )
            ->add(
                'faculties',
                EntityType::class,
                [
                    'class' => Faculty::class, 
                    'choice_label' => 'label',
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Faculty',
                    'attr' => ['class' => 'form-control']
                ]
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
            'data_class' => SchoolUser::class,
            'standalone' => false
        ]);
    }
}
