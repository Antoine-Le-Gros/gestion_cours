<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UserHoursMaxValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var UserHoursMax $constraint */

        if (!$constraint instanceof UserHoursMax) {
            throw new UnexpectedTypeException($constraint, UserHoursMax::class);
        }
        $user = $this->context->getObject();

        if (!$user instanceof User) {
            throw new UnexpectedValueException($value, 'App\Entity\User');
        }
        $roles = $user->getRoles();

        $min = 0;
        $max = PHP_INT_MAX;

        if (in_array(User::AGGREGATED, $roles) || in_array(User::CERTIFIED, $roles)) {
            $min = 384;
            $max = 768;
        } elseif (in_array(User::RESEARCHER, $roles)) {
            $min = 192;
            $max = 384;
        } elseif (in_array(User::EXTERNAL, $roles)) {
            $max = 192;
        }
        if ($value < $min || $value > $max) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->setParameter('{{ role }}', implode(', ', $roles))
                ->addViolation();
        }
    }
}
