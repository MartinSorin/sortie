<?php

namespace App\Form;

use App\Entity\Trip;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class TripCancelType extends AbstractType
{
    private Security $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'Nom de la sortie'])
            ->add('dateTimeStart', null, ['label' => 'Date de la sortie'])
            ->add('siteOrganiser', EntityType::class, ['label' => 'Campus', 'choice_label' => 'name', 'class' => 'App\Entity\Campus', 'data' => $this->security->getUser()->getIsAffectedTo()])
            ->add('city', EntityType::class, ['label' => 'Ville', 'choice_label' => 'name', 'class' => 'App\Entity\City', 'mapped' => false])
            ->add('place', EntityType::class, ['label' => 'Lieu', 'choice_label' => 'name', 'class' => 'App\Entity\Place'])
            ->add('motif', TextareaType::class, ['label' => 'Motif annulation', 'required' => false,'mapped' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
