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
            ->add('campus', EntityType::class, ['label' => 'Campus', 'choice_label' => 'name', 'class' => 'App\Entity\Campus'])
            ->add('search', TextType::class, ['label' => 'Le nom de la sortie contient', 'mapped' => false, 'attr' => ['placeholder' => 'search']])
            ->add('start', DateTimeType::class, ['label' => 'Entre', 'mapped' => false])
            ->add('end', DateTimeType::class, ['label' => 'et', 'mapped' => false])
            ->add('organizer', CheckboxType::class, ['label' => 'Sorties dont je suis l\'organisateur/trice', 'mapped' => false])
            ->add('registered', CheckboxType::class, ['label' => 'Sorties auxquelles je suis inscrit/e', 'mapped' => false])
            ->add('notRegistered', CheckboxType::class, ['label' => 'Sorties auxquelles je ne suis pas inscrit/e', 'mapped' => false])
            ->add('passed', CheckboxType::class, ['label' => 'Sorties passées', 'mapped' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
