<?php

namespace App\Form;

use App\Entity\PersonnalStat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use App\Entity\StatTag;
use App\Repository\StatTagRepository;


class PersonnalStatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['inEvent'])
        {
            $builder
                ->add('tag', EntityType::class, [
                    'class' => StatTag::class,
                    'choice_label' => 'name',
                    'query_builder' => function (StatTagRepository $rep)
                        {
                            return $rep->queryActivated();
                        }
                    ]
                )
                ->add('value')
                ->add('timer',null,[
                    'block_prefix' => 'switch'
                ])
                ->add('time', TimeType::class, [
                    'widget' => 'single_text',
                    'required' => false
                ])
            ;
        } else {
            $builder
                ->add('value')
                ->add('time')
                ->add('timer')
                ->add('tag')
                ->add('player')
                ->add('event')
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PersonnalStat::class,
            'inEvent' => false
        ]);

        $resolver->setAllowedTypes('inEvent', 'bool');
    }
}
