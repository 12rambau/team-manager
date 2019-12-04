<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\FieldTemplate;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\FieldTemplateType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class TemplateSelectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $fEvent){
            
            $event = $fEvent->getData(); //should be an instance of Event
            $team = $event->getTeam();

            $form = $fEvent->getForm();
            $form->add('fields', CollectionType::class, [

                'entry_type'=> FieldTemplateType::class,
                'entry_options' => [ 'team' => $team],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ]);

        });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
