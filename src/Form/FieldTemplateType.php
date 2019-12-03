<?php

namespace App\Form;

use App\Entity\Field;
use App\Entity\FieldTemplate;
use Symfony\Component\Form\AbstractType;
use App\Repository\FieldTemplateRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldTemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('template', EntityType::class, [
                'class' => FieldTemplate::class,
                'choice_label' => 'name',
                'query_builder' => function (FieldTemplateRepository $rep) {
                    return $rep->queryEnabled();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Field::class,
        ]);
    }
}
