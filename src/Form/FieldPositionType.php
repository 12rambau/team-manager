<?php

namespace App\Form;

use App\Entity\Participation;
use App\Entity\Position;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Field;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\DataTransformer\FieldToIdTransformer;
use App\DataTransformer\PositionToIdTransformer;

class FieldPositionType extends AbstractType
{
    private $ft; //FieldToIdTransformer
    private $pt; //PositionToIdTransformer

    public function __construct(FieldToIdTransformer $ft, PositionToIdTransformer $pt)
    {
        $this->pt = $pt;
        $this->ft = $ft;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position', TextType::class)
            ->add('field', TextType::class)
        ;

        $builder->get('position')->addModelTransformer($this->pt);
        $builder->get('field')->addModelTransformer($this->ft);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participation::class,
        ]);
    }
}
