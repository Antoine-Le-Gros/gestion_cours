<?php

namespace App\Controller\Admin;

use App\Entity\HourlyVolume;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class HourlyVolumeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HourlyVolume::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            NumberField::new('volume', 'volume'),
            AssociationField::new('course', 'cours')
                ->setFormTypeOption('choice_label', 'courseTitle.name')
                ->formatValue(function ($entity) {
                    return isset($entity) ? $entity->getCourseTitle()->getName() : 'Pas de cours';
                }),
            AssociationField::new('week', 'semaine')
                ->setFormTypeOption('choice_label', 'number')
                ->formatValue(function ($entity) {
                    return isset($entity) ? $entity->getNumber() : 'Pas de semaine';
                }),
        ];
    }
}
