<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Image extends Constraint
{
    public $message = "the picture have not been filled.";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}