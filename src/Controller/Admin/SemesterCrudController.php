<?php

namespace App\Controller\Admin;

use App\Entity\Semester;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class SemesterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Semester::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            IntegerField::new('number', 'numéro'),
            AssociationField::new('year', 'année')
                ->setFormTypeOption('choice_label', 'name')
                ->formatValue(function ($entity) {
                    return isset($entity) ? $entity->getName() : 'Pas d\'année';
                }),
        ];
    }
}
