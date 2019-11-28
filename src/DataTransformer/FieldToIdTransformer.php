<?php

namespace App\DataTransformer;

use App\Entity\Field;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FieldToIdTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function transform($field)
    {
        if (null === $field) {
            return '';
        }

        return $field->getId();
    }

    public function reverseTransform($id)
    {
        if (!$id){
            return;
        } 

        $field = $this->em->getRepository(Field::class)->find($id);

        if (null === $field){
            throw new TransformationFailedException(sprintf("the field '%s' does not exist!", $id));
        }

        return $field;

    }
}