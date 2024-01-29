<?php

namespace App\Form;

use App\Data\SearchData;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Hotel;

class SearchFormv extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder

           ->add ('marque',TextType::class,[
               'label'=>false,
               'required'=>false,
               'attr'=>[
                   'placeholder'=>'Marque ...'
                   ]

               ])
           ->add ('modele',TextType::class,[
               'label'=>false,
               'required'=>false,
               'attr'=>[
                   'placeholder'=>'Modele....'
               ]

           ])
           ->add ('couleur',TextType::class,[
               'label'=>false,
               'required'=>false,
               'attr'=>[
                   'placeholder'=>'Couleur....'
               ]

           ])

               ->add ('min', RangeType::class ,[
                 'label'=>false,
                  'required'=>false,
                   'attr' => [
                       'min' => 5,
                       'max' => 10000
                   ],
           ])
           ->add ('max', RangeType::class ,[
               'label'=>false,
               'required'=>false,
               'attr' => [
                   'min' => 5,
                   'max' => 10000
               ],
           ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data-class' => SearchData::class,
                'method'=>'GET',
                'csrf_protection'=>false
            ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}