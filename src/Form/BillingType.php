<?php

namespace App\Form;

use App\Entity\Billing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BillingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('adress')
            ->add('creationDate')
            ->add('brand')
            ->add('model')
            ->add('mileage')
            ->add('releaseDate')
            ->add('numberplate')
            ->add('serialNumber')
            ->add('tva')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'DEVIS' => 'DEVIS',
                    'FACTURE' => 'FACTURE',
                ],
                'label' => 'Status',
                'expanded' => false,
                'multiple' => false,
            ])            ->add('items', CollectionType::class, [
                'entry_type' => BillingItemType::class,
                'allow_add' => true,
                'by_reference' => false,
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Billing::class,
        ]);
    }
}
