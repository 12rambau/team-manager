<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\EventTag;
use App\Form\LocationType;
use App\Repository\EventTagRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tag', EntityType::class, [
                'class' => EventTag::class,
                'query_builder' => function (EventTagRepository $rep) {
                    return $rep->queryActivated();
                },
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => false,
                'block_prefix' => 'tag_choice'
            ])
            ->add('name')
            ->add(
                'start',
                DateTimeType::class,
                [
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                    //'date_format' => 'MM/dd/yyyy h:mm a',
                    'empty_data' => '',
                ]
            )
            ->add(
                'finish',
                DateTimeType::class,
                [
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                    //'date_format' => 'MM/dd/yyyy h:mm a'
                ]
            )
            ->add(
                'registerFinish',
                DateTimeType::class,
                [
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                    'required' => false,
                    'date_format' => 'MM/dd/yyyy h:mm a'
                ]
            )
            ->add('info', null, [
                'required' => false
            ])
            ->add('maxPlayers', null, [
                'required' => false
            ])
            ->add('location', LocationType::class, ['hidden' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
