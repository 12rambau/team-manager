<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('start', DateTimeType::class,[
                'date_widget'=>'single_text',
                'time_widget'=>'single_text'
                ]
            )
            ->add('finish', DateTimeType::class,[
                'date_widget'=>'single_text',
                'time_widget'=>'single_text'
                ]
            )
            ->add('registerFinish', DateTimeType::class,[
                'date_widget'=>'single_text',
                'time_widget'=>'single_text'
                ]
            )
            ->add('info')
            ->add('maxPlayers')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
