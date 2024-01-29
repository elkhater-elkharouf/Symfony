<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('destination',TextType::class,[
                'attr'=>['placeholder'=>"Entrez la destination",
                    'class' => 'form-control']])
            ->add('type',TextType::class,[
                'attr'=>['placeholder'=>"Entrez le type",
                    'class' => 'form-control']])
            ->add('prix',TextType::class,[
                'attr'=>['placeholder'=>"Entrez le prix",
                    'class' => 'form-control']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
