<?php

namespace App\Controller\Admin;

use App\Entity\Module;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ModuleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Module::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'nom'),
            AssociationField::new('semester', 'semestre')
                ->setFormTypeOption('choice_label', 'number')
                ->formatValue(function ($entity) {
                    return isset($entity) ? $entity->getNumber() : 'Pas de semestre';
                }),
            AssociationField::new('courseTitles', 'cours')
                ->setFormTypeOption('choice_label', 'name'),
        ];
    }
}
