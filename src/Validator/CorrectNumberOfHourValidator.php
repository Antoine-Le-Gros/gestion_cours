<?php

namespace App\Validator;

use App\Entity\Affectation;
use App\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CorrectNumberOfHourValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof Affectation) {
            return;
        }

        $user = $value->getTeacher();
        if (!$user instanceof User || !in_array(User::EXTERNAL, $user->getRoles())) {
            return;
        }

        $totalVolume = 0;
        foreach ($user->getAffectations() as $affectation) {
            if ($affectation !== $value) {
                $totalVolume += $affectation->getCourse()->getVolume() * $affectation->getNumberGroupTaken();
            }
        }

        // Include the new affectation in the total volume calculation
        $totalVolume += $value->getCourse()->getVolume() * $value->getNumberGroupTaken();

        if ($totalVolume > $user->getHoursMax()) {
            $this->context->buildViolation('The teacher has reached the maximum number of hours')
                ->atPath('teacher')
                ->addViolation();
        }
    }
}
