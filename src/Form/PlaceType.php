<?php

namespace App\Form;

use App\Entity\Place;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'Nom :', 'required' => false])
            ->add('street', null, ['label' => 'Rue :', 'required' => false])
            ->add('latitude', null, ['label' => 'Latitude :', 'required' => false])
            ->add('longitude', null, ['label' => 'Longitude :', 'required' => false])
            ->add('city', EntityType::class, ['label' => 'Ville :', 'choice_label' => 'name', 'class' => 'App\Entity\City'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
