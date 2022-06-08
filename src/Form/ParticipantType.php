<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', null, ['required' => false, 'label' => 'Pseudo :', 'empty_data' => ''])
            ->add('name', null, ['required' => false, 'label' => 'Nom :', 'empty_data' => ''])
            ->add('firstname', null, ['required' => false, 'label' => 'Prénom :', 'empty_data' => ''])
            ->add('phone', null, ['required' => false, 'label' => 'Téléphone :', 'empty_data' => ''])
            ->add('email', null, ['required' => false, 'label' => 'Email :', 'empty_data' => ''])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
                'options' => [
                    'attr' => ['class' => 'password-field']
                ],
                'required' => false,
                'first_options' => ['label' => 'mot de passe :'],
                'second_options' => ['label' => 'Confirmation :'], 'mapped' => false,])
            ->add('isAffectedTo', EntityType::class, ['label' => 'Campus :', 'choice_label' => 'name', 'class' => 'App\Entity\Campus',
                'required' => false, 'empty_data' => ''])
            ->add('imageProfile', FileType::class, [
            'label' => 'Image de profil',
            // unmapped means that this field is not associated to any entity property
            'mapped' => false,
            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,
            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Veuillez charger une image',
                ])
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
