<?php

namespace App\Form;

use App\Entity\Trip;
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
            ->add('duration', null, ['label' => 'Durée'])
            ->add('dateLimitInscription', null, ['label' => 'Date limite de l\'inscription'])
            ->add('nbInscriptionsMax', null, ['label' => 'Nombre de places'])
            ->add('infoTrip', null, ['label' => 'Descriptions et infos'])

            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
