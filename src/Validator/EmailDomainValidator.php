<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EmailDomainValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\EmailDomain $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $domain = substr($value, strpos($value, "@") + 1);
        if (in_array($domain, $constraint->blocked)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }

}