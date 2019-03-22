<?php 

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ImageValidator extends ConstraintValidator
{
    public function validate($protocol, Constraint $constraint)
    {
        if($protocol->getImageFile() === null && $protocol->getFileName() === null && $protocol->getUpdatedAt() === null)
        {
            return $this->context
                ->buildViolation($constraint->message)
                ->atPath('imageFile')
                ->addViolation()
                ;
        }
    }
}