<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Faculty;
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

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'gender',
                 ChoiceType::class, 
                [
                     "choices" => ["m" => "m", "f" => "f", "o" => "o"], 
                     'attr' => ['class' => 'form-control']
                ]
            )
            ->add('password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'first_options' => array('label' => 'Password', 'attr' => ['class' => 'form-control']),
                    'second_options' => array('label' => 'Repeat Password', 'attr' => ['class' => 'form-control'])
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
            ->setMethod('POST')
        ;

        if ($options['standalone']) {
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
            'data_class' => User::class,
            'standalone' => false
        ]);
    }
}
