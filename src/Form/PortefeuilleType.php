<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Commerce;
use App\Entity\Portefeuille;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortefeuilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('solde')
            ->add('user',EntityType::class,[
                'class'=>User::class,
                'choice_label'=>'nom'
            ])
            ->add('commerce',EntityType::class,[
                'class'=>Commerce::class,
                'choice_label'=>'nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Portefeuille::class,
        ]);
    }
}
