<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class,
                [
                    'choices' => [
                        'ADMINISTRATION',
                        'ENSEIGNANT_AGRÉGÉ',
                        'ENSEIGNANT_CERTIFIÉ',
                        'ENSEIGNANT_CHERCHEUR',
                        'VACATAIRE',
                    ],
                    'multiple' => true,
                ])
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('login', TextType::class)
            ->add('hoursMax', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
