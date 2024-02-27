<?php

namespace App\Form;

use App\Entity\Appointments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType; 
use Symfony\Component\Form\Extension\Core\Type\TextType; 


class NewAppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateTimeType::class, 
            [
                'attr' => [
                    'class' => 'input-date',
                    'id' => 'form-date'
                ]
            ])
            ->add('customer', TextType::class,
            [
                'attr' => [
                    'class' => 'input-date',
                    'id' => 'form-client'
                ]
            ])
            ->add('phone', TextType::class,  
            [
                'attr' => [
                    'class' => 'input-date',
                    'id' => 'form-telephone'
                ]
            ])
            ->add('car', TextType::class,
            [
                'attr' => [
                    'class' => 'input-date',
                    'id' => 'form-vehicule'
                ]
            ])
            ->add('reason', TextType::class,
            [
                'attr' => [
                    'class' => 'input-date',
                    'id' => 'form-purpose'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointments::class,
        ]);
    }
}
