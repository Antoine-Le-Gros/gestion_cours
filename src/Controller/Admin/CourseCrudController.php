<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CourseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Course::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('courseTitle', 'titre')
                ->setFormTypeOption('choice_label', 'name')
                ->formatValue(function ($entity) {
                    return isset($entity) ? $entity->getName() : 'Pas de titre';
                }),
            AssociationField::new('typeCourse', 'Type')
                ->setFormTypeOption('choice_label', 'name')
                ->formatValue(function ($entity) {
                    return isset($entity) ? $entity->getName() : 'Pas de type de cours défini';
                }),
            IntegerField::new('groupMaxNumber', 'nombre de groupes maximal'),
            TextField::new('SAESupport', 'Sae support'),
        ];
    }
}
