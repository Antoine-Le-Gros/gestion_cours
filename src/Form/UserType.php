<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
        $roles = User::TYPE_USER;
        array_splice($roles, 0, 1);
        $roleChoices = [];
        foreach ($roles as $role) {
            $roleChoices[str_replace('_', ' ', $role)] = $role;
        }
        $builder
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class,
                [
                    'choices' => $roleChoices,
                    'required' => false,
                    'multiple' => true,
                ])
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('login', TextType::class)
            ->add('hoursMax', IntegerType::class)
            ->add('isAdministration', CheckboxType::class,
                [
                    'required' => false,
                    'mapped' => false,
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
