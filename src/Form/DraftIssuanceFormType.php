<?php

namespace App\Form;

use App\Entity\Degree;
use App\Entity\Faculty;
use App\Entity\Issuance;
use App\Entity\DegreeType;
use App\Entity\SchoolUser;
use App\Entity\DegreeField;
use App\Entity\DegreeTitle;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DraftIssuanceFormType extends AbstractType
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
                    // 'placeholder' => array(
                    //     'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                    // )
                ]
                )
            ->add(
                'classYearEnd',
                DateType::class,
                [
                    'widget' => 'choice',
                    'label' => 'Ending year', 
                    'years' => range(2000,2020),
                    // 'placeholder' => array(
                    //     'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                    // )
                    
                ]
                )
           
            ->add(
                'faculty',
                EntityType::class,
                [
                    'class' => Faculty::class,
                    'placeholder' => 'Select a faculty',
                    'mapped' => false,

                    // 'query_builder' => function (EntityRepository $er) {
                    //     return $er->createQueryBuilder('u')
                    //         ->orderBy('u.username', 'ASC');
                    // }, 

                    'choice_label' => 'label',
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Faculty',
                    'attr' => ['class' => 'form-control filter-list-input']
                ]
                )
            // ->add(
            //     'degreeTitle',
            //     EntityType::class,
            //     [
            //         'class' => DegreeTitle::class,
            //         // 'query_builder' => function (EntityRepository $er) {
            //         //     return $er->createQueryBuilder('u')
            //         //         ->orderBy('u.username', 'ASC');
            //         // }, 
            //         'choice_label' => 'label',
            //         'expanded' => false,
            //         'multiple' => false,
            //         'label' => 'Faculty',
            //         'attr' => ['class' => 'form-control filter-list-input']
            //     ]
            //     )
   
            // ->add(
            //     'degreeType',
            //     EntityType::class,
            //     [
            //         'class' => DegreeType::class, 
            //         'choice_label' => 'label',
            //         'expanded' => false,
            //         'multiple' => false,
            //         'label' => 'Type of degree',
            //         'attr' => ['class' => 'form-control filter-list-input']
            //     ]
            //     )
            // ->add(
            //     'degreeType.degreeTitles',
            //     EntityType::class,
            //     [
            //         'class' => DegreeTitle::class, 
            //         'choice_label' => 'label',
            //         'expanded' => false,
            //         'multiple' => false,
            //         'label' => 'Title of degree',
            //         'attr' => ['class' => 'form-control filter-list-input']
            //     ]
                // )
            // ->add(
            //     'description',
            //     TextareaType::class,
            //     ['attr' => ['class' => 'form-control filter-list-input']]
            //     )
            // ->add(
            //     'dueDate',
            //     DateTimeType::class,
            //     [
            //         'widget' => 'choice',
            //         'label' => 'Due date', 
            //         'years' => range(2010,2020)
            //     ]
            //     )
            // ->add(    
            //     'schoolUser',
            //     EntityType::class,
            //     [
            //         'class' => SchoolUser::class, 
            //         'choice_label' => 'user.firstname',
            //         'expanded' => true,
            //         'multiple' => true,
            //         'label' => 'Faculty members',
            //         'attr' => ['class' => 'form-control filter-list-input']
            //     ]
            //     )
            // ->add(
            //     'rector',
            //     EntityType::class,
            //     [
            //         'class' => SchoolUser::class, 
            //         'choice_label' => 'user.firstname',
            //         'expanded' => false,
            //         'multiple' => false,
            //         'label' => 'Rector',
            //         'attr' => ['class' => 'form-control filter-list-input']
            //     ]
            //     )
        ;
        
        $builder->get('faculty')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event){
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                // $degreeFields = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                
                // $formModifierA($event->getForm()->getParent(), $faculty);

                $form = $event->getForm();
                $faculty = $form->getData();

                 

                $degreeFields = null === $faculty ? array() : $faculty->getDegreeField();

                $form->getParent()->add('degreeField', EntityType::class, 
                [
                    'class' => DegreeField::class,
                    'placeholder' => 'Select a study field',
                    'choice_label' => $degreeFields,
                    // 'expanded' => false,
                    // 'multiple' => false,
                    // 'label' => 'Field of study',
                    // 'attr' => ['class' => 'form-control filter-list-input']
                ]
                );
            }
        );
        

        // link degreeField to chosen faculty

        // $formModifierA = function (FormInterface $form, Faculty $faculty = null) {
        //     $degreeFields = null === $faculty ? array() : $faculty->getDegreeFields();

        //     $form->add('degreeField', EntityType::class, 
        //     [
        //         'class' => DegreeField::class,
        //         'choice_label' => 'label',
        //         'expanded' => false,
        //         'multiple' => false,
        //         'label' => 'Field of study',
        //         'attr' => ['class' => 'form-control filter-list-input']
        //     ]
        //     );
        // };

        // $builder->addEventListener(
        //     FormEvents::PRE_SET_DATA,
        //     function (FormEvent $event) use ($formModifierA) {
        //         // Our entity, Faculty
        //         $data = $event->getData();

        //         $formModifierA($event->getForm(), $data->getFaculty());
        //     }
        // );

        



        ////////////////////////////////////////////////////

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
            'data_class' => Issuance::class,
            'standalone' => false
        ]);
    }
}
