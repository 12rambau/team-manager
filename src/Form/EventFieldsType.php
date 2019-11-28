<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\FieldPositionType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class EventFieldsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $fEvent) {
            $form = $fEvent->getForm();
            $event = $fEvent->getData(); //should be an instance of Event
            $participations = $event->getParticipations();

            foreach ($participations as $p)
                if ($p->getValue() != true) $event->removeParticipation($p);

            $form->add('participations', CollectionType::class, [
                'entry_type' => FieldPositionType::class,
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
