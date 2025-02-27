<?php

namespace App\Form;

use App\Entity\Affectation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AffectationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('teacher', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return ucwords($user->getFirstName()).' '.strtoupper($user->getLastName().' | '.$user->getRemainingHours().' Heure(s) libre(s)');
                },
            ])
            ->add('numberGroupTaken', IntegerType::class, [
                'label' => 'Nombre de groupes pris',
                'empty_data' => 1,
                'attr' => [
                    'min' => 1,
                    'type' => 'number',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Affectation::class,
        ]);
    }
}
