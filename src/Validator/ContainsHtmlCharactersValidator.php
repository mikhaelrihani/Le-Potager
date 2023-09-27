<?php


namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsHtmlCharactersValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        // escape the value to avoid XSS attacks
        if (preg_match('/<[^>]*>/', $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
