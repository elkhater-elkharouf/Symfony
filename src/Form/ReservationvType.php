<?php

namespace App\Form;

use App\Entity\Reservationv;
use App\Entity\User;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datedebut',\Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('datefin',\Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('prixreservation')
            ->add('voiture',EntityType::class,[
                'class'=>Voiture::class,
                'choice_label'=>'marque',
                'multiple'=>false,
                'expanded'=>false,])
            ->add('user',EntityType::class,[
                'class'=>User::class,
                'choice_label'=>'nom',
                'multiple'=>false,
                'expanded'=>false,])
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservationv::class,
        ]);
    }
}