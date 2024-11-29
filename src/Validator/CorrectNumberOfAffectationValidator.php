<?php

namespace App\Validator;

use App\Entity\Affectation;
use App\Entity\Course;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CorrectNumberOfAffectationValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof Collection) {
            return;
        }

        $totalAssignedGroups = 0;
        foreach ($value as $item) {
            if (!$item instanceof Affectation) {
                return;
            }
            $totalAssignedGroups += $item->getNumberGroupTaken();
        }

        $course = $this->context->getObject();
        if (!$course instanceof Course) {
            return;
        }

        if ($totalAssignedGroups > $course->getGroupMaxNumber()) {
            $this->context->buildViolation("Nombre d'affectation invalide pour ce cours")
                ->atPath('affectations')
                ->addViolation();
        }
    }
}
