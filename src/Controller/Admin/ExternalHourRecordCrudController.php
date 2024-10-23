<?php

namespace App\Controller\Admin;

use App\Entity\ExternalHourRecord;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class ExternalHourRecordCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ExternalHourRecord::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            IntegerField::new('hours', 'nombre d\'heures'),
            AssociationField::new('teacher', 'profeseur')
                ->setFormTypeOption('choice_label', 'login')
                ->formatValue(function ($entity) {
                    return isset($entity) ? $entity->getlogin() : 'Pas de professeur';
                }),
            AssociationField::new('year', 'Année')
                ->setFormTypeOption('choice_label', 'name')
                ->formatValue(function ($entity) {
                    return isset($entity) ? $entity->getName() : 'Pas d\'année';
                }),
        ];
    }
}
