<?php

namespace App\Form;

use App\Entity\Field;
use App\Repository\FieldRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateSelectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('template', EntityType::class, [
            'class' => Field::class,
            'query_builder' => function (FieldRepository $rep) {
                return $rep->queryAllTemplate();
            },
            'choice_label' => 'name',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Null,
        ]);
    }
}
