<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, ['label' => 'Campus', 'choice_label' => 'name', 'class' => 'App\Entity\Campus', 'required' => false, 'attr' => ['placeholder' => 'Choisisez un campus']])
            ->add('search', TextType::class, ['label' => 'Le nom de la sortie contient', 'required' => false, 'attr' => ['placeholder' => 'search']])
            ->add('start', DateTimeType::class, ['label' => 'Entre', 'required' => false])
            ->add('end', DateTimeType::class, ['label' => 'et', 'required' => false])
            ->add('organiser', CheckboxType::class, ['label' => 'Sorties dont je suis l\'organisateur/trice', 'required' => false])
            ->add('registered', CheckboxType::class, ['label' => 'Sorties auxquelles je suis inscrit/e', 'required' => false])
            ->add('notRegistered', CheckboxType::class, ['label' => 'Sorties auxquelles je ne suis pas inscrit/e', 'required' => false])
            ->add('passed', CheckboxType::class, ['label' => 'Sorties passées', 'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

