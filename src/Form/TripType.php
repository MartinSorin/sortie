<?php

namespace App\Form;

use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'Nom de la sortie'])
            ->add('dateTimeStart', null, ['label' => 'Date et heure de la sortie'])
            ->add('duration', null, ['label' => 'Durée', 'attr' => ['value' => '90']])
            ->add('dateLimitInscription', null, ['label' => 'Date limite de l\'inscription'])
            ->add('nbInscriptionsMax', null, ['label' => 'Nombre de places'])
            ->add('infoTrip', null, ['label' => 'Descriptions et infos'])
            ->add('campus', EntityType::class, ['label' => 'Campus', 'choice_label' => 'name', 'class' => 'App\Entity\Campus', 'mapped' => false])
            ->add('city', EntityType::class, ['label' => 'Ville', 'choice_label' => 'name', 'class' => 'App\Entity\City', 'mapped' => false])
            ->add('place', EntityType::class, ['label' => 'Lieu', 'choice_label' => 'name', 'class' => 'App\Entity\Place'])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
