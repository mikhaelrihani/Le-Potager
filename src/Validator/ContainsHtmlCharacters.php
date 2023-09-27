<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsHtmlCharacters extends Constraint
{
    // message that will be shown if the validation fails
    public $message = 'La valeur "{{ value }}" contient des caractères HTML non autorisés.';
}
