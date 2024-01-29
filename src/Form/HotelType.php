<?php

namespace App\Form;

use App\Entity\Hotel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HotelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'attr'=>['placeholder'=>"Entrez la nom de l'hotel",
                    'class' => 'form-control']])
            ->add('etoile',TextType::class,[
                'attr'=>['placeholder'=>"Entrez la numero d'etoile",
                    'class' => 'form-control']])
            ->add('nombrechambre',TextType::class,[
                'attr'=>['placeholder'=>"Entrez le numero de chambre",
                    'class' => 'form-control']])
            ->add('adresse',TextType::class,[
                'attr'=>['placeholder'=>"Entrez l'adresse ",
                    'class' => 'form-control']])
            ->add('superficie',TextType::class,[
                'attr'=>['placeholder'=>"Entrez le superficie ",
                    'class' => 'form-control']])
            ->add('etage',TextType::class,[
                'attr'=>['placeholder'=>"Entrez le nombre d'etage ",
                    'class' => 'form-control']])
            ->add('prix',TextType::class,[
                'attr'=>['placeholder'=>"Entrez le prix d'une nuitée ",
                    'class' => 'form-control']])
            ->add('img', FileType::class,[
                'mapped' => false
            ])
            ->add('promotion',TextType::class,[
                'attr'=>['placeholder'=>"Indiquer si il est ou promotion ou non ? ",
                    'class' => 'form-control']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hotel::class,
        ]);
    }
}
