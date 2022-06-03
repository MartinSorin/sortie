<?php

namespace App\Form;

use App\Entity\Trip;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class TripModifyType extends AbstractType
{
    private Security $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'Nom de la sortie: ', 'required' => false , 'attr'=>['class'=>'champ']])
            ->add('dateTimeStart', null, ['label' => 'Date et heure de la sortie: ','attr'=>['class'=>'champdate'], 'date_widget' => 'single_text'])
            ->add('duration', null, ['label' => 'Durée: ', 'required' => false, 'attr'=>['class'=>'champ']])
            ->add('dateLimitInscription', null, ['label' => 'Date limite de l\'inscription: ', 'attr'=>['class'=>'champ'], 'widget' => 'single_text'])
            ->add('nbInscriptionsMax', null, ['label' => 'Nombre de places: ', 'required' => false, 'attr'=>['class'=>'champ']])
            ->add('infoTrip', null, ['label' => 'Descriptions et infos: ', 'required' => false, 'attr'=>['class'=>'champ']])
            ->add('siteOrganiser', EntityType::class, ['label' => 'Campus: ', 'choice_label' => 'name', 'class' => 'App\Entity\Campus', 'data' => $this->security->getUser()->getIsAffectedTo(), 'attr'=>['class'=>'champselect']])
            ->add('place', EntityType::class, ['label' => 'Lieu: ', 'choice_label' => 'name', 'class' => 'App\Entity\Place', 'attr'=>['class'=>'champselect']])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
