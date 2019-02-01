<?php

namespace App\Form;

use App\Entity\Faculty;
use App\Entity\SchoolUser;
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

class SchoolUserSettingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username', 
                TextType::class, 
                [
                    "label" => "Username", 
                    'attr' => ['class' => 'form-control text-left']
                ]
            )
            ->add(
                'firstname', 
                TextType::class, 
                [
                    "label" => "Firstname", 
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'lastname', 
                TextType::class, 
                [
                    "label" => "Lastname", 
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'gender',
                 ChoiceType::class, 
                [
                     "choices" => ["m" => "m", "f" => "f", "o" => "o"], 
                     'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'phoneFix', 
                TextType::class, 
                [
                    "label" => "phone", 
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'phoneMobile', 
                TextType::class, 
                [
                    "label" => "mobile", 'attr' => ['class' => 'form-control']
                ]
            )
            
            ->add(
                'schoolPosition',
                TextType::class, 
                [
                    "label" => "work position", 
                    'attr' => ['class' => 'form-control text-left']
                ]
                )
            ->add(
                'birthDate',
                DateType::class,
                [
                    'widget' => 'choice', 
                    "label" => "Date of birth", 
                    'years' => range(1950,2000)
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