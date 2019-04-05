<?php

namespace App\Form;

use App\Entity\Position;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Participation;
use App\Repository\ParticipationRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LinkPositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $formEvent) {
                $form = $formEvent->getForm();
                $entity = $formEvent->getData(); //should be an instance of position
                $event = $entity->getField()->getEvent();

                $form->add('participation', EntityType::class, [
                    'required' => false,
                    'expanded' => true,
                    'class' => Participation::class,
                    'query_builder' => function (ParticipationRepository $rep) use ($event) {
                        return $rep->queryAllIn($event);
                    },
                    'choice_label' => 'user.userName',
                ]);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Position::class,
        ]);
    }
}
