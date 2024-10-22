<?php

namespace App\Controller\Admin;

use App\Entity\Affectation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class AffectationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Affectation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            IntegerField::new('numberGroupTaken', 'nombre de groupes'),
            AssociationField::new('teacher', 'profeseur')
                ->setFormTypeOption('choice_label', 'login')
                ->formatValue(function ($entity) {
                    return isset($entity) ? $entity->getlogin() : 'Pas de professeur';
                }),
            AssociationField::new('course', 'Cours')
                ->setFormTypeOption('choice_label', 'courseTitle.name')
                ->formatValue(function ($entity) {
                    return isset($entity) ? $entity->getCourseTitle()->getName() : 'Pas de cours';
                }),
        ];
    }
}
