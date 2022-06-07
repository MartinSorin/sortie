<?php

namespace App\Form;

use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class TripType extends AbstractType
{
    private Security $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'Nom de la sortie : ', 'required' => false])
            ->add('dateTimeStart', DateTimeType::class, ['label' => 'Date et heure de la sortie : ', 'widget' => 'single_text'])
            ->add('duration', null, ['label' => 'Durée : ', 'required' => false])
            ->add('dateLimitInscription', DateType::class, ['label' => 'Date limite de l\'inscription : ', 'widget' => 'single_text'])
            ->add('nbInscriptionsMax', null, ['label' => 'Nombre de places : ', 'required' => false])
            ->add('infoTrip', null, ['label' => 'Descriptions et infos : ', 'required' => false])
            ->add('siteOrganiser', EntityType::class, ['label' => 'Campus : ', 'choice_label' => 'name', 'class' => 'App\Entity\Campus', 'data' => $this->security->getUser()->getIsAffectedTo()])
            ->add('city', EntityType::class, ['label' => 'Ville : ', 'choice_label' => 'name', 'class' => 'App\Entity\City', 'mapped' => false])
            ->add('place', EntityType::class, ['label' => 'Lieu : ', 'choice_label' => 'name', 'class' => 'App\Entity\Place'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
