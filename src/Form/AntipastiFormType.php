<?php

namespace App\Form;

use App\Entity\Antipasti;
use App\Entity\Piatto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;

class AntipastiFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'plat1',
                EntityType::class, 
                [
                    'class' => Piatto::class,
                    'choice_label' => 'italien',
                    'attr' => ['class' => 'form-control form-control'],
                    'label'=> 'Piatto Premier',
                    // 'query_builder' => function (EntityRepository $er) {
                    //     return $er->createQueryBuilder('p')
                    //     ->where('p.piattoType = :val')
                    //     ->setParameter('val', 1)
                    //     ->getQuery()
                    //     ->getResult();
                    // }
                ])
            ->add(
                'plat2',
                EntityType::class, 
                [
                    'class' => Piatto::class,
                    'choice_label' => 'italien',
                    'attr' => ['class' => 'form-control form-control'],
                    'label'=> 'Piatto Secondo'
                ])
            ->add(
                'plat3',
                EntityType::class, 
                [
                    'class' => Piatto::class,
                    'choice_label' => 'italien',
                    'attr' => ['class' => 'form-control form-control'],
                    'label'=> 'Piatto Terzi'
                ])
            ->add(
                'plat4',
                EntityType::class, 
                [
                    'class' => Piatto::class,
                    'choice_label' => 'italien',
                    'attr' => ['class' => 'form-control form-control'],
                    'label'=> 'Piatto Quarto'
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
            'data_class' => Antipasti::class,
            'standalone' => false
        ]);
    }
}
