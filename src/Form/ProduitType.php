<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type',TextType::class,[
        'attr'=>['placeholder'=>"Entrez le type ",
            'class' => 'form-control']])
            ->add('couleur',TextType::class,[
        'attr'=>['placeholder'=>"Entrez la couleur",
            'class' => 'form-control']])
            ->add('prix',TextType::class,[
                'attr'=>['placeholder'=>"Entrez le prix",
                    'class' => 'form-control']])
            ->add('quantite',TextType::class,[
        'attr'=>['placeholder'=>"Entrez votre quantite",
            'class' => 'form-control']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
