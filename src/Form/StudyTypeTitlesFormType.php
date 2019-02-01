<?php

namespace App\Form;

use App\Entity\DegreeType;
use App\Entity\StudyTypeTitles;
use App\Entity\DegreeTitle;
use App\Form\DegreeTitleFormType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use App\Repository\DegreeFieldRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\Extension\Core\EventListener\TrimListener;
use Symfony\Component\Form\Extension\Csrf\EventListener\CsrfValidationListener;

class StudyTypeTitlesFormType extends AbstractType
{

    public $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'degreeType',
                EntityType::class, 
                [
                    'class' => DegreeType::class, 
                    'choice_label' => 'label',
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Type',
                    'attr' => ['class' => 'form-control filter-list-input'],
                    'data_class' => null
                ]
                )
            // ->add(
            //     'degreeField',
            //     HiddenType::class
            //     )
            ->add(
                'degreeTitles', 
                CollectionType::class, 
                    [
                        'entry_type' => DegreeTitleFormType::class,
                        'entry_options' => [
                            
                            'label' => false
                            
                            ],
                        'by_reference' => false,
                        'allow_add' => true,
                        'allow_delete' => true
                        
                    ]
                );
            // ->addEventListener(
            //     FormEvents::PRE_SUBMIT,
            //     array(
            //         $this, 
            //         'onPreSubmit'
            //         )
            // );
        

        if ($options['standalone']) {
            $builder->add(
                'submit',
                SubmitType::class,
                ['attr' => ['class' => "btn btn-primary"], 'label' => 'Save']
            );
        }    
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StudyTypeTitles::class,
            'standalone' => false
        ]);

    }

    

    // public function onPreSubmit(FormEvent $event, $entityManager)
    // {
        
    //     $degreeFieldId = $event->getData();

    //     if ($degreeFieldId['degreeField']) {

    //         $degreeField = $this->$entityManager->getRepository(DegreeField::class)->find($degreeFieldId);
    //         // createQuery('SELECT c FROM App\Entity\DegreeField c WHERE c.id = :degreeFieldId');
            
    //         return $degreeField->execute();

    //         $data->setDegreeField($degreeField);   

    //     }

        // $degreeField = $entityManager->createQueryBuilder('d')
        //                             ->from(DegreeFieldRepository::class)
        //                             ->andWhere('d.id = :val')
        //                             ->setParameter('val', $degreeFieldId)
        //                             ->getQuery()
        //                             ->getResult();
        
        // ->createQuery('SELECT c FROM DegreeField::class c ORDER BY c.name ASC')
        // ->getResult();
        
        // getRepository(DegreeField::class)->findOneById($degreeFieldId);
 
        
 
    // }
}
