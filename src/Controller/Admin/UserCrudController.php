<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('lastname', 'Nom'),
            TextField::new('firstname', 'Prénom'),
            TextField::new('email', 'Email'),
            TextField::new('login', 'Identifiant'),
            BooleanField::new('isActive', 'Activité'),
            IntegerField::new('hoursMax', 'Volumes d\'heures maximales'),
            ArrayField::new('roles', 'Roles'),
            AssociationField::new('externalHourRecords', 'Heures externes')
                ->setFormTypeOption('choice_label', 'hours')
                ->setFormTypeOption('by_reference', false),
            AssociationField::new('affectations', 'Affectations')
                ->setFormTypeOption('choice_label', 'course.courseTitle.name')
                ->setFormTypeOption('by_reference', false),
        ];
    }
}
