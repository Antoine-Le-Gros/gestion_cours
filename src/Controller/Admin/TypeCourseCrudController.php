<?php

namespace App\Controller\Admin;

use App\Entity\TypeCourse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TypeCourseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeCourse::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'nom'),
        ];
    }
}
