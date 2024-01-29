<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use   Symfony\Component\Form\Extension\Core\Type\FileType;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule',TextType::class,[
                'attr'=>['placeholder'=>"Entrez la matricule",
                    'class' => 'form-control']])
            ->add('modele',TextType::class,[
                'attr'=>['placeholder'=>"Entrez le modele",
                    'class' => 'form-control']])
            ->add('marque',TextType::class,[
                'attr'=>['placeholder'=>"Entrez la marque",
                    'class' => 'form-control']])
            ->add('couleur',TextType::class,[
                'attr'=>['placeholder'=>"Entrez la couleur",
                    'class' => 'form-control']])
            ->add('transmission',TextType::class,[
                'attr'=>['placeholder'=>"Entrez la transmission",
                    'class' => 'form-control']])
            ->add('bagage',TextType::class,[
                'attr'=>['placeholder'=>"Entrez le nombre de bagages",
                    'class' => 'form-control']])
            ->add('place',TextType::class,[
                'attr'=>['placeholder'=>"Entrez le nombre de places",
                    'class' => 'form-control']])
            ->add('carburant',TextType::class,[
                'attr'=>['placeholder'=>"Entrez la carburant",
                    'class' => 'form-control']])
            ->add('prix',TextType::class,[
                'attr'=>['placeholder'=>"Entrez le prix",
                    'class' => 'form-control']])
            ->add('killometrage',TextType::class,[
                'attr'=>['placeholder'=>"Entrez le killometrage",
                    'class' => 'form-control']])
            ->add('image', FileType::class,[
                'mapped' => false ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}