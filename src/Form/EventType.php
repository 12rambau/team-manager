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

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tag', EntityType::class, [
                'class' => EventTag::class,
                'choice_label'=>'name',
                'expanded'=>true,
                'multiple'=>false
            ])
            ->add('name')
            ->add('start', DateTimeType::class,[
                'date_widget'=>'single_text',
                'time_widget'=>'single_text',
                'date_format'=>'MM/dd/yyyy h:mm a'
                ]
            )
            ->add('finish', DateTimeType::class,[
                'date_widget'=>'single_text',
                'time_widget'=>'single_text',
                'date_format'=>'MM/dd/yyyy h:mm a'
                ]
            )
            ->add('registerFinish', DateTimeType::class,[
                'date_widget'=>'single_text',
                'time_widget'=>'single_text',
                'required'=>false,
                'date_format'=>'MM/dd/yyyy h:mm a'
                ]
            )
            ->add('info', null,[
                'required'=>false
            ])
            ->add('maxPlayers', null,[
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
