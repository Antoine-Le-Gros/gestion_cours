<?php

namespace App\Controller\Admin;

use App\Entity\CourseTitle;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CourseTitleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CourseTitle::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'titre'),
            AssociationField::new('modules', 'modules')
                ->setFormTypeOption('choice_label', 'name'),
            AssociationField::new('tags', 'tags')
                ->setFormTypeOption('choice_label', 'name'),
        ];
    }
}
