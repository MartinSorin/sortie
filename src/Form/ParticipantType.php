<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',null, ['required'=> false,'label' => 'Pseudo :', 'empty_data' => ''])
            ->add('name',null, ['required'=> false,'label' => 'Nom :', 'empty_data' => ''])
            ->add('firstname',null, ['required'=> false,'label' => 'Prénom :', 'empty_data' => ''])
            ->add('phone',null, ['required'=> false,'label' => 'Téléphone :', 'empty_data' => ''])
            ->add('email',null, ['required'=> false,'label' => 'Email :', 'empty_data' => ''])
            ->add('password', RepeatedType::class, ['type' => PasswordType::class, 'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']], 'required' => true, 'first_options'  => ['label' => 'mot de passe :'],
                'second_options' => ['label' => 'Confirmation :'],])
            ->add('isAffectedTo', EntityType::class, ['label' => 'Campus', 'choice_label' => 'name', 'class' => 'App\Entity\Campus',
        'required' => false, 'empty_data' => '']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
