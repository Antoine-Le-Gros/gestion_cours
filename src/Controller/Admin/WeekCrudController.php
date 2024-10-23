<?php

namespace App\Controller\Admin;

use App\Entity\Week;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class WeekCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Week::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            IntegerField::new('number', 'nombre'),
            AssociationField::new('semesters', 'semestre')
                ->setFormTypeOption('choice_label', 'number')
                ->formatValue(function ($entity) {
                    return isset($entity) ? $entity->getNumber() : 'Pas de semestre';
                }),
        ];
    }
}
