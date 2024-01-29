<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;



class UpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('nom',TextType::class,[
                'attr'=>['placeholder'=>"Entrez votre nom",
                    'class' => 'form-control']])
            ->add('prenom',TextType::class,[
                'attr'=>['placeholder'=>"Entrez votre prenom",
                    'class' => 'form-control']])
            ->add('telephone',TelType::class,[
                'attr'=>['placeholder'=>"Entrez votre téléphone",
                    'class' => 'form-control']])
            ->add('date_naissance',DateType::class,[
                'attr'=>['placeholder'=>"Entrez votre date de naissance",
                    'class' => 'form-control']])
                
            ->add('mail',TextType::class,[
                'attr'=>['placeholder'=>"Entrez votre e-mail",
                    'class' => 'form-control']])
            ->add('Mettre a jour', SubmitType::class, ['attr'=>['class' => 'btn btn-success']])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
