<?php

namespace App\DataTransformer;

use App\Entity\Position;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PositionToIdTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function transform($position)
    {
        if (null === $position) {
            return '';
        }

        return $position->getId();
    }

    public function reverseTransform($id)
    {
        if (!$id){
            return;
        } 

        $position = $this->em->getRepository(Position::class)->find($id);

        if (null === $position){
            throw new TransformationFailedException(sprintf("the position '%s' does not exist!", $id));
        }

        return $position;

    }
}