<?php

namespace App\Form;

use App\Entity\Formule;
use App\Entity\Support;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control rounded-1',
                    'placeholder' => 'Votre adresse mail'
                ]
            ])
            ->add('formule', EntityType::class, [
                'class' => Formule::class,
                'label' => false,
                'attr' => [
                    'id' => 'selectFormule',
                    'class' => 'form-control select-input placeholder-active active'
                ]
            ])
            ->add('support', EntityType::class, [
                'class' => Support::class,
                'label' => false,
                'attr' => [
                    'id' => 'support',
                    'class' => 'form-control select-input placeholder-active active'
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control rounded-1',
                    'placeholder' => 'Prénom'
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control rounded-1',
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('tel', NumberType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control rounded-1',
                    'placeholder' => 'Numéro de téléphone'
                ]
            ])
            ->add('sujet', TextType::class, [
                'label' => false,   
                'attr' => [
                    'class' => 'form-control rounded-1',
                    'placeholder' => 'Sujet du message'
                ]
            ])
            ->add('message', CKEditorType::class, [
                'label' => false,
            ])
            ->add('Envoyer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn email'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
